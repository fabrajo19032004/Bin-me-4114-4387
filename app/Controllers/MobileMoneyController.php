<?php

namespace App\Controllers;

use App\Models\BaremeFraisModel;
use App\Models\ClientModel;
use App\Models\PrefixModel;
use App\Models\TransactionModel;
use App\Models\TypeOperationModel;
use App\Models\UserModel;

class MobileMoneyController extends BaseController
{
    public function index()
    {
        $session = session();

        if (!$session->get('connecte')) {
            return redirect()->to(base_url('auth/login'));
        }

        if ($session->get('role') === 'OPERATEUR') {
            return redirect()->to(base_url('mobile-money/operator'));
        }

        return redirect()->to(base_url('mobile-money/client'));
    }

    public function login()
    {
        return view('mobile_money/login');
    }

    public function loginPost()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if (empty($username) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Veuillez remplir tous les champs.');
        }

        $userModel = new UserModel();
        $user = $userModel->authenticate($username, $password);

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Identifiants invalides.');
        }

        $this->storeSession($user);

        return redirect()->to(base_url('mobile-money'));
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to(base_url('mobile-money/login'));
    }

    public function clientDashboard()
    {
        if (!session()->get('connecte')) {
            return redirect()->to(base_url('mobile-money/login'));
        }

        $clientModel = new ClientModel();
        $transactionModel = new TransactionModel();
        $client = $clientModel->find(session()->get('client_id'));
        $transactions = $transactionModel->getByClient((int) $client['id']);

        return view('mobile_money/client_dashboard', [
            'client' => $client,
            'transactions' => $transactions,
        ]);
    }

    public function operatorDashboard()
    {
        if (!session()->get('connecte')) {
            return redirect()->to(base_url('mobile-money/login'));
        }

        $clientModel = new ClientModel();
        $transactionModel = new TransactionModel();
        $clients = $clientModel->findAll();
        $transactions = $transactionModel->findAll();

        return view('mobile_money/operator_dashboard', [
            'clients' => $clients,
            'transactions' => $transactions,
        ]);
    }

    public function deposit()
    {
        if (!session()->get('connecte')) {
            return redirect()->to(base_url('mobile-money/login'));
        }

        $telephone = $this->request->getPost('telephone');
        $montant = (float) $this->request->getPost('montant');

        if (empty($telephone) || $montant <= 0) {
            return redirect()->back()->with('error', 'Téléphone et montant sont requis.');
        }

        $clientModel = new ClientModel();
        $transactionModel = new TransactionModel();
        $typeOperationModel = new TypeOperationModel();
        $baremeModel = new BaremeFraisModel();

        $client = $clientModel->firstOrCreate($telephone);
        $typeOperationId = $typeOperationModel->getIdByName('Depot');
        $frais = $baremeModel->calculerFrais($typeOperationId, $montant);
        $newBalance = $clientModel->getBalance($client['id']) + $montant;

        $clientModel->updateBalance($client['id'], $newBalance);
        $transactionModel->record($typeOperationId, null, $client['id'], $montant, $frais);

        return redirect()->back()->with('success', 'Dépôt enregistré avec succès.');
    }

    public function withdraw()
    {
        if (!session()->get('connecte')) {
            return redirect()->to(base_url('mobile-money/login'));
        }

        $telephone = $this->request->getPost('telephone');
        $montant = (float) $this->request->getPost('montant');

        if (empty($telephone) || $montant <= 0) {
            return redirect()->back()->with('error', 'Téléphone et montant sont requis.');
        }

        $clientModel = new ClientModel();
        $transactionModel = new TransactionModel();
        $typeOperationModel = new TypeOperationModel();
        $baremeModel = new BaremeFraisModel();

        $client = $clientModel->firstOrCreate($telephone);
        $typeOperationId = $typeOperationModel->getIdByName('Retrait');
        $frais = $baremeModel->calculerFrais($typeOperationId, $montant);
        $total = $montant + $frais;
        $currentBalance = $clientModel->getBalance($client['id']);

        if ($currentBalance < $total) {
            return redirect()->back()->with('error', 'Solde insuffisant.');
        }

        $clientModel->updateBalance($client['id'], $currentBalance - $total);
        $transactionModel->record($typeOperationId, $client['id'], null, $montant, $frais);

        return redirect()->back()->with('success', 'Retrait enregistré avec succès.');
    }

    public function transfer()
    {
        if (!session()->get('connecte')) {
            return redirect()->to(base_url('mobile-money/login'));
        }

        $senderTelephone = $this->request->getPost('sender_telephone');
        $recipientTelephone = $this->request->getPost('recipient_telephone');
        $montant = (float) $this->request->getPost('montant');

        if (empty($senderTelephone) || empty($recipientTelephone) || $montant <= 0) {
            return redirect()->back()->with('error', 'Tous les champs sont requis.');
        }

        $clientModel = new ClientModel();
        $transactionModel = new TransactionModel();
        $typeOperationModel = new TypeOperationModel();
        $baremeModel = new BaremeFraisModel();

        $sender = $clientModel->firstOrCreate($senderTelephone);
        $recipient = $clientModel->firstOrCreate($recipientTelephone);
        $typeOperationId = $typeOperationModel->getIdByName('Transfert');
        $frais = $baremeModel->calculerFrais($typeOperationId, $montant);
        $total = $montant + $frais;
        $senderBalance = $clientModel->getBalance($sender['id']);

        if ($senderBalance < $total) {
            return redirect()->back()->with('error', 'Solde insuffisant pour effectuer le transfert.');
        }

        $clientModel->updateBalance($sender['id'], $senderBalance - $total);
        $clientModel->updateBalance($recipient['id'], $clientModel->getBalance($recipient['id']) + $montant);
        $transactionModel->record($typeOperationId, $sender['id'], $recipient['id'], $montant, $frais);

        return redirect()->back()->with('success', 'Transfert enregistré avec succès.');
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
