<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\PrefixModel;
use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login()
    {
        return view('auth/login_choice');
    }

    public function loginAdmin()
    {
        return view('auth/login_admin');
    }

    public function loginClient()
    {
        return view('auth/login_client');
    }

    public function loginPost()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if (empty($username) || empty($password)) {
            return redirect()->to(base_url('auth/login'))
                ->withInput()
                ->with('error', 'Veuillez remplir tous les champs.');
        }

        $userModel = new UserModel();
        $user = $userModel->authenticate($username, $password);

        if (!$user && $this->looksLikePhone($username)) {
            $prefixModel = new PrefixModel();
            if (!$prefixModel->isAllowed($username)) {
                return redirect()->to(base_url('auth/login'))
                    ->withInput()
                    ->with('error', 'Ce préfixe n’est pas autorisé.');
            }

            $clientModel = new ClientModel();
            $clientModel->firstOrCreate($username);
            $user = $userModel->createClientUser($username, $password);
        }

        if (!$user && $this->looksLikePhone($username) && $password !== '') {
            $prefixModel = new PrefixModel();
            if ($prefixModel->isAllowed($username)) {
                $clientModel = new ClientModel();
                $clientModel->firstOrCreate($username);
                $user = $userModel->createClientUser($username, $password);
            }
        }

        if (!$user) {
            return redirect()->to(base_url('auth/login'))
                ->withInput()
                ->with('error', 'Identifiants invalides.');
        }

        session()->set([
            'connecte' => true,
            'user_id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role'],
            'client_id' => $user['client_id'],
        ]);

        $role = strtoupper($user['role'] ?? 'CLIENT');
        $target = $role === 'OPERATEUR' ? 'mobile-money/operator' : 'mobile-money/client';

        return redirect()->to(base_url($target));
    }

    public function loginAdminPost()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if (empty($username) || empty($password)) {
            return redirect()->to(base_url('auth/login/admin'))
                ->withInput()
                ->with('error', 'Veuillez remplir tous les champs.');
        }

        $userModel = new UserModel();
        $user = $userModel->authenticate($username, $password);

        if (!$user || strtoupper((string) ($user['role'] ?? '')) !== 'OPERATEUR') {
            return redirect()->to(base_url('auth/login/admin'))
                ->withInput()
                ->with('error', 'Identifiants admin invalides.');
        }

        $this->storeSession($user);

        return redirect()->to(base_url('mobile-money/operator'));
    }

    public function loginClientPost()
    {
        $prefix = $this->request->getPost('prefixe');
        $restPhone = $this->request->getPost('rest_phone');

        if (empty($prefix) || empty($restPhone)) {
            return redirect()->to(base_url('auth/login/client'))
                ->withInput()
                ->with('error', 'Veuillez remplir tous les champs.');
        }

        $telephone = $this->buildPhoneNumber($prefix, $restPhone);

        if ($telephone === '') {
            return redirect()->to(base_url('auth/login/client'))
                ->withInput()
                ->with('error', 'Le numéro est invalide.');
        }

        $prefixModel = new PrefixModel();
        if (!$prefixModel->isAllowed($telephone)) {
            return redirect()->to(base_url('auth/login/client'))
                ->withInput()
                ->with('error', 'Ce préfixe n’est pas autorisé.');
        }

        $userModel = new UserModel();
        $user = $userModel->where('username', $telephone)->first();

        $clientModel = new ClientModel();
        $client = $clientModel->findByTelephone($telephone);

        if (!$client) {
            return redirect()->to(base_url('auth/login/client'))
                ->withInput()
                ->with('error', 'Ce numéro client n’existe pas.');
        }

        if (!$user) {
            $user = $userModel->createClientUser($telephone);
        }

        if (!$user || strtoupper((string) ($user['role'] ?? '')) !== 'CLIENT') {
            return redirect()->to(base_url('auth/login/client'))
                ->withInput()
                ->with('error', 'Identifiants client invalides.');
        }

        $this->storeSession($user);

        return redirect()->to(base_url('mobile-money/client'));
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
        return redirect()->to(base_url('auth/login'))
            ->with('success', 'Cette partie n\'est pas activée dans cette version simple.');
    }

    public function changerMotDePasseNouveau()
    {
        return redirect()->to(base_url('auth/login'));
    }

    public function changerMotDePasseNouveauPost()
    {
        return redirect()->to(base_url('auth/login'));
    }

    private function looksLikePhone(string $value): bool
    {
        return preg_match('/^0[0-9]{8,9}$/', $value) === 1;
    }

    private function buildPhoneNumber(string $prefix, string $restPhone): string
    {
        $cleanPrefix = preg_replace('/\D+/', '', $prefix) ?? '';
        $cleanRest = preg_replace('/\D+/', '', $restPhone) ?? '';

        if ($cleanPrefix === '' || $cleanRest === '') {
            return '';
        }

        return $cleanPrefix . $cleanRest;
    }

    private function storeSession(array $user): void
    {
        session()->set([
            'connecte' => true,
            'user_id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role'],
            'client_id' => $user['client_id'],
        ]);
    }
}
