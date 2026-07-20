<?php

namespace App\Controllers;

use App\Models\BaremeFraisModel;
use App\Models\ClientModel;
use App\Models\PrefixModel;
use App\Models\TransactionModel;
use App\Models\TypeOperationModel;
use App\Models\UserModel;
use App\Models\OperateurModel;


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

    public function saveCommission()
    {
        $redirect = $this->ensureOperator();
        if ($redirect !== null) {
            return $redirect;
        }

        $id = (int) $this->request->getPost('id');
        $commission = (float) $this->request->getPost('commission_pourcentage');

        $operateurModel = new OperateurModel();
        $operateurModel->update($id, ['commission_pourcentage' => $commission]);

        return redirect()->back()->with('success', 'Commission mise à jour.');
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
        $operateurModel = new OperateurModel();

        $clients = $clientModel->findAll();
        $transactions = $transactionModel->findAll();
        $prefixes = $prefixModel->orderBy('prefixe', 'ASC')->findAll();
        $operations = $typeOperationModel->orderBy('nom', 'ASC')->findAll();
        $fees = $feeModel->orderBy('type_operation_id', 'ASC')->orderBy('montant_min', 'ASC')->findAll();
        $operateurs = $operateurModel->findAll();

        // Récupérer l'ID du type "Transfert" pour les gains
        $typeTransfert = $typeOperationModel->where('nom', 'Transfert')->first();
        $typeTransfertId = $typeTransfert ? $typeTransfert['id'] : null;

        return view('mobile_money/operator_dashboard', [
            'clients' => $clients,
            'transactions' => $transactions,
            'prefixes' => $prefixes,
            'operations' => $operations,
            'fees' => $fees,
            'operateurs' => $operateurs,
            'typeTransfertId' => $typeTransfertId,
        ]);
    }


    public function savePrefix()
    {
        $redirect = $this->ensureOperator();
        if ($redirect !== null) {
            return $redirect;
        }

        $prefixe = trim($this->request->getPost('prefixe'));
        $operateurId = (int) $this->request->getPost('operateur_id');
        $estLocal = (int) $this->request->getPost('est_local');

        if ($prefixe === '') {
            return redirect()->back()->with('error', 'Le préfixe est requis.');
        }

        $prefixModel = new PrefixModel();
        $prefixModel->insert([
            'prefixe' => $prefixe,
            'operateur_id' => $operateurId,
            'est_local' => $estLocal
        ]);

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
        $inclureFrais = (bool) $this->request->getPost('inclure_frais');

        if (empty($telephone) || $montant <= 0) {
            return redirect()->back()->with('error', 'Téléphone et montant sont requis.');
        }

        $clientModel = new ClientModel();
        $transactionModel = new TransactionModel();
        $typeOperationModel = new TypeOperationModel();
        $baremeModel = new BaremeFraisModel();
        $prefixModel = new PrefixModel();

        $client = $clientModel->firstOrCreate($telephone);
        $typeOperationId = $typeOperationModel->getIdByName('Retrait');

        // Vérifier si le numéro est local
        $prefixInfo = $prefixModel->where('prefixe', substr($telephone, 0, 3))->first();
        $estLocal = ($prefixInfo && $prefixInfo['est_local'] == 1);

        // Calcul des frais (uniquement si local)
        if ($estLocal) {
            $frais = $baremeModel->calculerFrais($typeOperationId, $montant);
        } else {
            $frais = 0;
        }

        if ($inclureFrais) {
            $totalADebiter = $montant + $frais;
            $montantRecu = $montant;
            $message = "Retrait effectué. Vous avez reçu " . number_format($montantRecu, 2) . " € (frais : " . number_format($frais, 2) . " €).";
        } else {
            $totalADebiter = $montant;
            $montantRecu = $montant - $frais;
            $message = "Retrait effectué. Montant reçu : " . number_format($montantRecu, 2) . " €. Frais prélevés : " . number_format($frais, 2) . " €.";
        }

        if ($montantRecu < 0) {
            return redirect()->back()->with('error', 'Le montant est inférieur aux frais.');
        }

        $currentBalance = $clientModel->getBalance($client['id']);
        if ($currentBalance < $totalADebiter) {
            return redirect()->back()->with('error', 'Solde insuffisant.');
        }

        $clientModel->updateBalance($client['id'], $currentBalance - $totalADebiter);
        $transactionModel->record($typeOperationId, $client['id'], null, $montantRecu, $frais, 0);

        return redirect()->back()->with('success', $message);
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
        $prefixModel = new PrefixModel();
        $operateurModel = new OperateurModel();

        $sender = $clientModel->firstOrCreate($senderTelephone);
        $recipient = $clientModel->firstOrCreate($recipientTelephone);
        $typeOperationId = $typeOperationModel->getIdByName('Transfert');

        // Vérifier si le destinataire est externe
        $prefixInfo = $prefixModel->where('prefixe', substr($recipientTelephone, 0, 3))->first();
        $estExterne = ($prefixInfo && $prefixInfo['operateur_id'] != 1);

        // 1. Frais de base selon barème (inchangé)
        $frais = $baremeModel->calculerFrais($typeOperationId, $montant);

        // 2. Commission externe (si destinataire externe)
        $commission = 0;
        if ($estExterne) {
            $operateur = $operateurModel->find($prefixInfo['operateur_id']);
            if ($operateur) {
                // Commission calculée sur le montant envoyé
                $commission = $montant * ($operateur['commission_pourcentage'] / 100);
            }
        }

        // 3. Total débité = montant + frais + commission
        $totalADebiter = $montant + $frais + $commission;

        // Vérifier le solde de l'expéditeur
        $senderBalance = $clientModel->getBalance($sender['id']);
        if ($senderBalance < $totalADebiter) {
            return redirect()->back()->with('error', 'Solde insuffisant pour effectuer le transfert.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // 4. Débiter l'expéditeur (montant + frais + commission)
        $clientModel->updateBalance($sender['id'], $senderBalance - $totalADebiter);

        // 5. Créditer le destinataire (montant total)
        $recipientBalance = $clientModel->getBalance($recipient['id']);
        $clientModel->updateBalance($recipient['id'], $recipientBalance + $montant);

        // 6. Enregistrer la transaction
        // - Le montant reçu par le destinataire est le montant total
        // - Les frais totaux (pour l'opérateur local) = frais de base + commission
        $fraisTotaux = $frais + $commission;
        $transactionModel->record(
            $typeOperationId,
            $sender['id'],
            $recipient['id'],
            $montant,                // montant reçu par le destinataire
            $frais + $commission,    // total des frais (base + commission)
            $estExterne ? 1 : 0      // ← ICI : doit être 1 pour externe
        );

        $db->transComplete();

        // 7. Message de confirmation
        $message = "Transfert de " . number_format($montant, 2) . " € effectué. Le destinataire a reçu " . number_format($montant, 2) . " €.";
        if ($commission > 0) {
            $message .= " Commission externe : " . number_format($commission, 2) . " €.";
        }
        $message .= " Frais de base : " . number_format($frais, 2) . " €.";

        return redirect()->back()->with('success', $message);
    }

    public function transferMultiple()
    {
        if (!session()->get('connecte')) {
            return redirect()->to(base_url('mobile-money/login'));
        }

        $numeros = array_filter(array_map('trim', explode("\n", $this->request->getPost('numeros'))));
        $montantTotal = (float) $this->request->getPost('montant_total');

        if (count($numeros) < 2) {
            return redirect()->back()->with('error', 'Veuillez saisir au moins 2 numéros.');
        }
        if ($montantTotal <= 0) {
            return redirect()->back()->with('error', 'Le montant total doit être positif.');
        }

        $clientModel = new ClientModel();
        $transactionModel = new TransactionModel();
        $typeOperationModel = new TypeOperationModel();
        $baremeModel = new BaremeFraisModel();
        $prefixModel = new PrefixModel();

        $expediteur = $clientModel->find(session()->get('client_id'));
        if (!$expediteur) {
            return redirect()->back()->with('error', 'Client introuvable.');
        }

        $montantParDestinataire = $montantTotal / count($numeros);
        $typeOperationId = $typeOperationModel->getIdByName('Transfert');

        // Vérification : tous les destinataires doivent être du même opérateur (local)
        $destinataires = [];
        $totalADebiter = 0;
        $operateurIds = [];

        foreach ($numeros as $telephone) {
            if (!$prefixModel->isAllowed($telephone)) {
                return redirect()->back()->with('error', "Le numéro $telephone n'est pas valide.");
            }

            $prefixInfo = $prefixModel->where('prefixe', substr($telephone, 0, 3))->first();
            $operateurIds[] = $prefixInfo['operateur_id'];

            // Vérification : tous doivent être locaux (operateur_id = 1)
            if ($prefixInfo['operateur_id'] != 1) {
                return redirect()->back()->with('error', "Le numéro $telephone appartient à un autre opérateur. Les transferts multiples sont limités aux numéros locaux.");
            }

            $destinataire = $clientModel->firstOrCreate($telephone);
            $frais = $baremeModel->calculerFrais($typeOperationId, $montantParDestinataire);

            // Pour les transferts multiples, on applique les frais normaux (pas de commission externe)
            $totalParTransaction = $montantParDestinataire + $frais;
            $totalADebiter += $totalParTransaction;

            $destinataires[] = [
                'telephone' => $telephone,
                'destinataire_id' => $destinataire['id'],
                'montant' => $montantParDestinataire,
                'frais' => $frais,
            ];
        }

        if ($expediteur['solde'] < $totalADebiter) {
            return redirect()->back()->with('error', 'Solde insuffisant.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $clientModel->updateBalance($expediteur['id'], $expediteur['solde'] - $totalADebiter);

        foreach ($destinataires as $dest) {
            $destinataireActuel = $clientModel->find($dest['destinataire_id']);
            $clientModel->updateBalance($dest['destinataire_id'], $destinataireActuel['solde'] + $dest['montant']);
            $transactionModel->record($typeOperationId, $expediteur['id'], $dest['destinataire_id'], $dest['montant'], $dest['frais'], 0);
        }

        $db->transComplete();

        return redirect()->back()->with('success', count($destinataires) . ' transferts effectués avec succès.');
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
