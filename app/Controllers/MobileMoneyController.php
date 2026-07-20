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

        $role = strtoupper((string) $session->get('role'));

        if ($role === 'OPERATEUR') {
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

        $this->initializeDatabaseFromSql();

        $clientModel = new ClientModel();
        $transactionModel = new TransactionModel();
        $typeOperationModel = new TypeOperationModel();
        $client = $clientModel->find(session()->get('client_id'));
        $transactions = $transactionModel->getByClient((int) $client['id']);
        $operations = $typeOperationModel->findAll();
        $operationNames = [];

        foreach ($operations as $operation) {
            $operationNames[(int) $operation['id']] = $operation['nom'];
        }

        foreach ($transactions as &$transaction) {
            $transaction['type_nom'] = $operationNames[(int) ($transaction['type_operation_id'] ?? 0)] ?? 'Inconnu';
        }

        return view('mobile_money/client_dashboard', [
            'client' => $client,
            'transactions' => $transactions,
        ]);
    }

    public function operatorDashboard()
    {
        $redirect = $this->ensureOperator();
        if ($redirect !== null) {
            return $redirect;
        }

        $this->initializeDatabaseFromSql();

        $clientModel = new ClientModel();
        $transactionModel = new TransactionModel();
        $prefixModel = new PrefixModel();
        $typeOperationModel = new TypeOperationModel();
        $feeModel = new BaremeFraisModel();

        $clients = $clientModel->findAll();
        $transactions = $transactionModel->findAll();
        $prefixes = $prefixModel->orderBy('prefixe', 'ASC')->findAll();
        $operations = $typeOperationModel->orderBy('nom', 'ASC')->findAll();
        $fees = $feeModel->orderBy('type_operation_id', 'ASC')->orderBy('montant_min', 'ASC')->findAll();

        return view('mobile_money/operator_dashboard', [
            'clients' => $clients,
            'transactions' => $transactions,
            'prefixes' => $prefixes,
            'operations' => $operations,
            'fees' => $fees,
        ]);
    }

    public function savePrefix()
    {
        $redirect = $this->ensureOperator();
        if ($redirect !== null) {
            return $redirect;
        }

        $prefixe = trim($this->request->getPost('prefixe'));
        if ($prefixe === '') {
            return redirect()->back()->with('error', 'Le préfixe est requis.');
        }

        $prefixModel = new PrefixModel();
        $prefixModel->insert(['prefixe' => $prefixe, 'operateur_id' => 1]);

        return redirect()->back()->with('success', 'Préfixe ajouté avec succès.');
    }

    public function deletePrefix($id)
    {
        $redirect = $this->ensureOperator();
        if ($redirect !== null) {
            return $redirect;
        }

        $prefixModel = new PrefixModel();
        $prefixModel->delete((int) $id);

        return redirect()->back()->with('success', 'Préfixe supprimé.');
    }

    public function saveOperation()
    {
        $redirect = $this->ensureOperator();
        if ($redirect !== null) {
            return $redirect;
        }

        $nom = trim($this->request->getPost('nom'));
        if ($nom === '') {
            return redirect()->back()->with('error', 'Le nom de l’opération est requis.');
        }

        $typeOperationModel = new TypeOperationModel();
        $typeOperationModel->insert(['nom' => $nom]);

        return redirect()->back()->with('success', 'Type d’opération ajouté.');
    }

    public function deleteOperation($id)
    {
        $redirect = $this->ensureOperator();
        if ($redirect !== null) {
            return $redirect;
        }

        $typeOperationModel = new TypeOperationModel();
        $typeOperationModel->delete((int) $id);

        return redirect()->back()->with('success', 'Type d’opération supprimé.');
    }

    public function saveFee()
    {
        $redirect = $this->ensureOperator();
        if ($redirect !== null) {
            return $redirect;
        }

        $feeModel = new BaremeFraisModel();
        $feeModel->insert([
            'type_operation_id' => (int) $this->request->getPost('type_operation_id'),
            'montant_min' => (float) $this->request->getPost('montant_min'),
            'montant_max' => (float) $this->request->getPost('montant_max'),
            'frais' => (float) $this->request->getPost('frais'),
        ]);

        return redirect()->back()->with('success', 'Barème ajouté.');
    }

    public function deleteFee($id)
    {
        $redirect = $this->ensureOperator();
        if ($redirect !== null) {
            return $redirect;
        }

        $feeModel = new BaremeFraisModel();
        $feeModel->delete((int) $id);

        return redirect()->back()->with('success', 'Barème supprimé.');
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

    private function ensureOperator()
    {
        if (!session()->get('connecte')) {
            return redirect()->to(base_url('mobile-money/login'));
        }

        if (strtoupper((string) session()->get('role')) !== 'OPERATEUR') {
            return redirect()->to(base_url('mobile-money/client'));
        }

        return null;
    }

    private function initializeDatabaseFromSql(): void
    {
        $sqlFile = ROOTPATH . 'base.sql';
        if (!is_file($sqlFile)) {
            return;
        }

        $db = db_connect();
        $tableCheck = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'")->getResultArray();
        if (!empty($tableCheck)) {
            return;
        }

        $sql = file_get_contents($sqlFile);
        if ($sql === false || trim($sql) === '') {
            return;
        }

        $sql = preg_replace('/^sqlite3\s+.+$/m', '', $sql);
        $sql = preg_replace('/^\s*--.*$/m', '', $sql);
        $sql = preg_replace('/\r\n/', "\n", $sql);
        $sql = trim($sql);

        if ($sql === '') {
            return;
        }

        $statements = preg_split('/;\s*\n/', $sql);

        foreach ($statements as $statement) {
            $statement = trim($statement);
            if ($statement === '') {
                continue;
            }

            $db->query($statement);
        }
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
