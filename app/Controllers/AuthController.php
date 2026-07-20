<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends BaseController
{
    public function login()
    {
        // Empêche le cache navigateur sur cette page aussi : sinon, après
        // déconnexion, un "retour arrière" peut réafficher une ancienne
        // version (connecté) du formulaire de login depuis le cache local.
        $this->response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                        ->setHeader('Pragma', 'no-cache')
                        ->setHeader('Expires', '0');

        // Politique de sécurité voulue : la page de connexion ne redirige
        // JAMAIS automatiquement vers un dashboard, même si une session
        // valide existe déjà. L'utilisateur doit systématiquement
        // re-soumettre ses identifiants pour accéder à l'application.
        // On détruit donc toute session active dès qu'on atterrit ici.
        if (session()->get('connecte')) {
            session()->destroy();
        }

        return view('auth/login');
    }

    public function loginPost()
    {
        $email      = $this->request->getPost('email');
        $motDePasse = $this->request->getPost('mot_de_passe');

        if (empty($email) || empty($motDePasse)) {
            return redirect()->to(base_url('auth/login'))
                ->withInput()
                ->with('error', 'Veuillez remplir tous les champs.');
        }

        $model       = new UserModel();
        $utilisateur = $model->where('email', $email)->first();

        if (!$utilisateur) {
            return redirect()->to(base_url('auth/login'))
                ->withInput()
                ->with('error', 'Adresse e-mail introuvable.');
        }

        // 1. Mot de passe normal → connexion classique
        if ($this->verifierMotDePasse($motDePasse, $utilisateur['mot_de_passe'])) {
            $this->creerSession($utilisateur);
            return $this->redirectParRole();
        }

        // 2. Sinon, est-ce un mot de passe TEMPORAIRE valide (usage unique) ?
        if ($model->verifierMotDePasseTemporaire($utilisateur, $motDePasse)) {
            // On ouvre une mini-session "changement de mdp obligatoire" :
            // l'utilisateur n'est PAS encore connecté normalement, il doit
            // d'abord définir son nouveau mot de passe (étape 2).
            session()->set([
                'changement_mdp_user_id' => $utilisateur['id'],
                'changement_mdp_etape'   => 2, // étape 1 (saisie du mdp temp) déjà validée
            ]);

            return redirect()->to(base_url('auth/changer-mot-de-passe/nouveau'));
        }

        return redirect()->to(base_url('auth/login'))
            ->withInput()
            ->with('error', 'Mot de passe incorrect.');
    }

    public function registerAdmin()
    {
        return view('auth/register_admin'); // Créez une vue simple avec nom, email, mot_de_passe
    }

    public function registerAdminPost()
    {
        $nom        = $this->request->getPost('nom');
        $email      = $this->request->getPost('email');
        $motDePasse = $this->request->getPost('mot_de_passe');

        if (empty($nom) || empty($email) || empty($motDePasse)) {
            return redirect()->to(base_url('auth/register-admin-secret-gate'))
                ->withInput()
                ->with('error', 'Veuillez remplir tous les champs.');
        }

        $model = new UserModel();
        
        $data = [
            'nom'          => $nom,
            'email'        => $email,
            'mot_de_passe' => $motDePasse,
            'role'         => 'admin'
        ];

        if ($model->insert($data)) {
            return redirect()->to(base_url('auth/login'))
                ->with('success', 'Compte administrateur créé avec succès. Vous pouvez maintenant vous connecter.');
        }

        return redirect()->back()->withInput()->with('error', 'Erreur lors de la création du compte.');
    }

    /**
     * Vérifie le mot de passe (Supporte le Hash ET le texte clair de la base actuelle)
     */
    private function verifierMotDePasse($saisi, $stocke)
    {
        // 1. Tente la vérification par Hash (Standard CI4)
        if (password_verify($saisi, $stocke)) {
            return true;
        }
        
        // 2. Fallback: Comparaison directe (Pour supporter 'admin123' sans modifier la DB)
        return $saisi === $stocke;
    }

    /**
     * Centralisation de la création de session
     */
    private function creerSession($utilisateur)
    {
        session()->set([
            'connecte' => true,
            'user_id'  => $utilisateur['id'],
            'nom'      => $utilisateur['nom'],
            'email'    => $utilisateur['email'],
            'role'     => $utilisateur['role'],
        ]);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('auth/login'))
            ->with('success', 'Vous avez été déconnecté avec succès.');
    }

    public function motDePasseOublie()
    {
        return view('auth/mot_de_passe_oublie');
    }

    public function motDePasseOubliePost()
    {
        $email   = $this->request->getPost('email');
        $message = $this->request->getPost('message');

        if (empty($email)) {
            return redirect()->to(base_url('auth/mot-de-passe-oublie'))
                ->withInput()
                ->with('error', 'Veuillez saisir votre adresse e-mail.');
        }

        $model       = new UserModel();
        $utilisateur = $model->where('email', $email)->first();

        if (!$utilisateur) {
            // On ne révèle pas si l'email existe ou non (sécurité)
            return redirect()->to(base_url('auth/mot-de-passe-oublie'))
                ->with('success', 'Si cet e-mail existe, votre demande a été envoyée à l\'administrateur.');
        }

        // Marque le compte comme "demande de réinitialisation en attente"
        // → visible par l'administrateur (notification) dans la gestion des utilisateurs.
        $model->signalerDemandeOubli((int) $utilisateur['id']);

        return redirect()->to(base_url('auth/mot-de-passe-oublie'))
            ->with('success', 'Votre demande a bien été transmise à l\'administrateur. Il vous communiquera un mot de passe temporaire sous peu.');
    }

    // ═══════════════════════════════════════════════════════════════════════
    //  CHANGEMENT DE MOT DE PASSE EN 2 ÉTAPES
    //  Étape 1 (saisie du mot de passe temporaire) = se fait directement sur
    //  la page de connexion classique (auth/login), voir loginPost() ci-dessus.
    //  Étape 2 (saisie du nouveau mot de passe définitif) = page dédiée
    //  ci-dessous, accessible uniquement après validation de l'étape 1.
    // ═══════════════════════════════════════════════════════════════════════

    /**
     * Étape 2 — affiche le formulaire de saisie du NOUVEAU mot de passe.
     * Accessible uniquement si l'étape 1 (mdp temporaire) a été validée
     * (cf. session 'changement_mdp_etape' positionnée dans loginPost()).
     */
    public function changerMotDePasseNouveau()
    {
        if (!session()->get('changement_mdp_user_id') || session()->get('changement_mdp_etape') < 2) {
            return redirect()->to(base_url('auth/login'))
                ->with('error', 'Veuillez d\'abord vous connecter avec votre mot de passe temporaire.');
        }

        return view('auth/changer_mot_de_passe_nouveau');
    }

    /**
     * Traite la soumission du nouveau mot de passe (fin de l'étape 2).
     * Enregistre le nouveau mot de passe définitif, invalide le mot de passe
     * temporaire (usage unique) puis connecte l'utilisateur.
     */
    public function changerMotDePasseNouveauPost()
    {
        $userId = session()->get('changement_mdp_user_id');

        if (!$userId || session()->get('changement_mdp_etape') < 2) {
            return redirect()->to(base_url('auth/login'))
                ->with('error', 'Session de changement de mot de passe expirée. Veuillez recommencer.');
        }

        $nouveauMdp   = $this->request->getPost('nouveau_mot_de_passe');
        $confirmation = $this->request->getPost('confirmation_mot_de_passe');

        if (empty($nouveauMdp) || empty($confirmation)) {
            return redirect()->back()->with('error', 'Veuillez remplir tous les champs.');
        }

        if (strlen($nouveauMdp) < 6) {
            return redirect()->back()->with('error', 'Le mot de passe doit contenir au moins 6 caractères.');
        }

        if ($nouveauMdp !== $confirmation) {
            return redirect()->back()->with('error', 'Les deux mots de passe ne correspondent pas.');
        }

        $model      = new UserModel();
        $utilisateur = $model->find($userId);

        if (!$utilisateur) {
            session()->remove(['changement_mdp_user_id', 'changement_mdp_etape']);
            return redirect()->to(base_url('auth/login'))->with('error', 'Utilisateur introuvable.');
        }

        $model->finaliserChangementMotDePasse((int) $userId, $nouveauMdp);

        // Nettoyage de la session de changement de mdp
        session()->remove(['changement_mdp_user_id', 'changement_mdp_etape']);

        // Connexion automatique avec le nouveau mot de passe
        $this->creerSession($utilisateur);

        session()->setFlashdata('success', 'Votre mot de passe a été changé avec succès.');
        return $this->redirectParRole();
    }

    private function redirectParRole()
    {
        $role = session()->get('role');

        switch ($role) {
            case 'admin':
                return redirect()->to(base_url('admin/dashboard'));
            case 'agent_commercial':
                return redirect()->to(base_url('agent/dashboard'));
            case 'magasinier':
                return redirect()->to(base_url('magasinier/stocks'));
            case 'livreur':
                return redirect()->to(base_url('livreur/reservations'));
            default:
                session()->destroy();
                return redirect()->to(base_url('auth/login'))
                    ->with('error', 'Rôle non reconnu. Contactez l\'administrateur.');
        }
    }
}
