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
    ];

    // Timestamps (présents dans table.sql)
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Soft Deletes (colonne deleted_at présente dans table.sql)
    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $beforeUpdate   = ['hashPassword'];

    /**
     * Hash automatique du mot de passe (champ 'mot_de_passe') avant INSERT ou UPDATE.
     * Le mot de passe temporaire ('mot_de_passe_temp') est haché séparément
     * via genererMotDePasseTemporaire(), il n'est donc PAS touché ici.
     */
    protected function hashPassword(array $data): array
    {
        if (isset($data['data']['mot_de_passe'])) {
            $data['data']['mot_de_passe'] = password_hash(
                $data['data']['mot_de_passe'],
                PASSWORD_DEFAULT
            );
        }

        return $data;
    }

    /**
     * Durée de validité (en minutes) d'un mot de passe temporaire.
     */
    public const DUREE_VALIDITE_MINUTES = 60;

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
            'mot_de_passe_temp'  => null,
            'mdp_temp_expire_at' => null,
        ]);
    }
}
