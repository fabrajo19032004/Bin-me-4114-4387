<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
        'nom',
        'email',
        'mot_de_passe',
        'mot_de_passe_temp',
        'mdp_temp_expire_at',
        'demande_reinit',
        'role',
        'username',
        'password',
    ];

    // La base SQLite actuelle ne contient pas ces colonnes.
    // On désactive ces comportements pour éviter les requêtes SQL invalides.
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $useSoftDeletes = false;
    protected $deletedField   = 'deleted_at';

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $beforeUpdate   = ['hashPassword'];

    /**
     * Hash automatique du mot de passe avant INSERT ou UPDATE.
     * Supporte à la fois les colonnes modernes et les anciennes colonnes SQLite.
     */
    protected function hashPassword(array $data): array
    {
        if (isset($data['data']['mot_de_passe'])) {
            $data['data']['mot_de_passe'] = password_hash(
                $data['data']['mot_de_passe'],
                PASSWORD_DEFAULT
            );
        }

        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash(
                $data['data']['password'],
                PASSWORD_DEFAULT
            );
        }

        return $data;
    }

    protected function getExistingColumns(): array
    {
        $columns = $this->db->getFieldNames($this->table);

        return is_array($columns) ? array_map('strval', $columns) : [];
    }

    protected function normalizeDataForStorage(array $data): array
    {
        $existingColumns = $this->getExistingColumns();
        $normalized       = [];

        foreach ($data as $key => $value) {
            if (in_array($key, $existingColumns, true)) {
                $normalized[$key] = $value;
                continue;
            }

            if ($key === 'email' && in_array('username', $existingColumns, true)) {
                $normalized['username'] = $value;
                continue;
            }

            if ($key === 'username' && in_array('email', $existingColumns, true)) {
                $normalized['email'] = $value;
                continue;
            }

            if ($key === 'mot_de_passe' && in_array('password', $existingColumns, true)) {
                $normalized['password'] = $value;
                continue;
            }

            if ($key === 'password' && in_array('mot_de_passe', $existingColumns, true)) {
                $normalized['mot_de_passe'] = $value;
                continue;
            }

            if ($key === 'nom' && in_array('username', $existingColumns, true) && !isset($normalized['username'])) {
                $normalized['username'] = $value;
            }
        }

        return $normalized;
    }

    public function insert($data = null, bool $returnID = true)
    {
        if (is_array($data)) {
            $data = $this->normalizeDataForStorage($data);
        }

        return parent::insert($data, $returnID);
    }

    public function update($id = null, $data = null): bool
    {
        if (is_array($data)) {
            $data = $this->normalizeDataForStorage($data);
        }

        return parent::update($id, $data);
    }

    /**
     * Durée de validité (en minutes) d'un mot de passe temporaire.
     */
    public const DUREE_VALIDITE_MINUTES = 60;

    public function findForLogin(string $identifier): ?array
    {
        $identifier = trim($identifier);
        if ($identifier === '') {
            return null;
        }

        $columns = $this->getExistingColumns();

        if (in_array('email', $columns, true)) {
            return $this->where('email', $identifier)->first();
        }

        if (in_array('username', $columns, true)) {
            return $this->where('username', $identifier)->first();
        }

        return null;
    }

    public function verifierMotDePasseCompat(string $saisi, array $utilisateur): bool
    {
        $motDePasseStocke = $utilisateur['mot_de_passe'] ?? $utilisateur['password'] ?? null;

        if (empty($motDePasseStocke)) {
            return false;
        }

        if (password_verify($saisi, $motDePasseStocke)) {
            return true;
        }

        return $saisi === $motDePasseStocke;
    }

    /**
     * Marque un compte comme ayant signalé un oubli de mot de passe.
     * Ce flag s'affiche comme notification côté admin tant qu'il n'est
     * pas traité (= tant qu'un mdp temporaire n'a pas été généré).
     */
    public function signalerDemandeOubli(int $userId): bool
    {
        return (bool) $this->update($userId, ['demande_reinit' => 1]);
    }

    /**
     * Génère un mot de passe temporaire à usage unique pour un utilisateur,
     * le hache et l'enregistre avec une date d'expiration.
     *
     * @param int $userId
     * @return string|false Le mot de passe temporaire EN CLAIR (à communiquer une seule
     *                       fois à l'utilisateur), ou false en cas d'échec.
     */
    public function genererMotDePasseTemporaire(int $userId)
    {
        // Mot de passe lisible, facile à transmettre oralement / par notification
        // Ex: "TX9K-4827"
        $motDePasseClair = strtoupper(bin2hex(random_bytes(2))) . '-' . random_int(1000, 9999);

        $expireA = date('Y-m-d H:i:s', strtotime('+' . self::DUREE_VALIDITE_MINUTES . ' minutes'));

        $ok = $this->update($userId, [
            'mot_de_passe_temp'  => password_hash($motDePasseClair, PASSWORD_DEFAULT),
            'mdp_temp_expire_at' => $expireA,
            'demande_reinit'     => 0, // la demande est traitée
        ]);

        return $ok ? $motDePasseClair : false;
    }

    /**
     * Vérifie un mot de passe temporaire saisi par l'utilisateur.
     * Retourne true si valide ET non expiré.
     */
    public function verifierMotDePasseTemporaire(array $user, string $saisi): bool
    {
        if (empty($user['mot_de_passe_temp']) || empty($user['mdp_temp_expire_at'])) {
            return false;
        }

        if (strtotime($user['mdp_temp_expire_at']) < time()) {
            return false; // expiré
        }

        return password_verify($saisi, $user['mot_de_passe_temp']);
    }

    /**
     * Finalise le changement de mot de passe : enregistre le nouveau mot de passe
     * définitif et invalide le mot de passe temporaire (usage unique).
     */
    public function finaliserChangementMotDePasse(int $userId, string $nouveauMotDePasse): bool
    {
        return (bool) $this->update($userId, [
            'mot_de_passe'       => $nouveauMotDePasse, // sera haché par hashPassword()
            'password'           => $nouveauMotDePasse, // compatibilité ancienne base
            'mot_de_passe_temp'  => null,
            'mdp_temp_expire_at' => null,
        ]);
    }
}
