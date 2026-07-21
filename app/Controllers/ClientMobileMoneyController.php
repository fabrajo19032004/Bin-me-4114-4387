<?php

namespace App\Controllers;

use App\Models\BaremeFraisModel;
use App\Models\ClientModel;
use App\Models\PrefixModel;
use App\Models\TransactionModel;
use App\Models\TypeOperationModel;

class ClientMobileMoneyController extends MobileMoneyController
{
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

        return view('mobile_money/client/dashboard_page', [
            'client' => $client,
            'transactions' => $transactions,
            'title' => 'Accueil client',
            'role' => 'client',
            'roleLabel' => 'Client',
            'page' => 'dashboard',
        ]);
    }

    public function clientHistory()
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

        return view('mobile_money/client/history_page', [
            'client' => $client,
            'transactions' => $transactions,
            'title' => 'Historique client',
            'role' => 'client',
            'roleLabel' => 'Client',
            'page' => 'history',
        ]);
    }

    public function clientTransfers()
    {
        if (!session()->get('connecte')) {
            return redirect()->to(base_url('mobile-money/login'));
        }

        $this->initializeDatabaseFromSql();

        $clientModel = new ClientModel();
        $client = $clientModel->find(session()->get('client_id'));

        return view('mobile_money/client/transfers_page', [
            'client' => $client,
            'title' => 'Transferts client',
            'role' => 'client',
            'roleLabel' => 'Client',
            'page' => 'transfers',
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

        $prefixInfo = $prefixModel->where('prefixe', substr($telephone, 0, 3))->first();
        $estLocal = ($prefixInfo && $prefixInfo['est_local'] == 1);

        if ($estLocal) {
            $frais = $baremeModel->calculerFrais($typeOperationId, $montant);
        } else {
            $frais = 0;
        }

        if ($inclureFrais) {
            $totalADebiter = $montant + $frais;
            $montantRecu = $montant;
            $message = "Retrait effectué. Vous avez reçu " . number_format($montantRecu, 2) . " Ar (frais : " . number_format($frais, 2) . " Ar).";
        } else {
            $totalADebiter = $montant;
            $montantRecu = $montant - $frais;
            $message = "Retrait effectué. Montant reçu : " . number_format($montantRecu, 2) . " Ar. Frais prélevés : " . number_format($frais, 2) . " Ar.";
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
        $operateurModel = new \App\Models\OperateurModel();
        $reductionModel = new \App\Models\ReductionModel();

        $sender = $clientModel->firstOrCreate($senderTelephone);
        $recipient = $clientModel->firstOrCreate($recipientTelephone);
        $typeOperationId = $typeOperationModel->getIdByName('Transfert');

        $prefixInfo = $prefixModel->where('prefixe', substr($recipientTelephone, 0, 3))->first();
        $estExterne = ($prefixInfo && $prefixInfo['operateur_id'] != 1);

        $frais = $baremeModel->calculerFrais($typeOperationId, $montant);

        $commission = 0;
        if ($estExterne) {
            $operateur = $operateurModel->find($prefixInfo['operateur_id']);
            if ($operateur) {
                $commission = $montant * ($operateur['commission_pourcentage'] / 100);
            }
        }

        $reductions = 0;
        if ($estExterne) {
            $operateur = $reductionModel->find($prefixInfo['operateur_id']);
            if ($operateur) {
                $reductions = ($montant - $frais) * ($operateur['reductions_pourcentage'] / 100);
            }
        }


        $totalADebiter = $montant + $frais + $commission +  $reductions;

        $senderBalance = $clientModel->getBalance($sender['id']);
        if ($senderBalance < $totalADebiter) {
            return redirect()->back()->with('error', 'Solde insuffisant pour effectuer le transfert.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $clientModel->updateBalance($sender['id'], $senderBalance - $totalADebiter);

        $recipientBalance = $clientModel->getBalance($recipient['id']);
        $clientModel->updateBalance($recipient['id'], $recipientBalance + $montant);

        $transactionModel->record(
            $typeOperationId,
            $sender['id'],
            $recipient['id'],
            $montant,
            $frais + $commission,
            $estExterne ? 1 : 0
        );

        $db->transComplete();

        $message = "Transfert de " . number_format($montant, 2) . " Ar effectué. Le destinataire a reçu " . number_format($montant, 2) . " Ar.";
        if ($commission > 0) {
            $message .= " Commission externe : " . number_format($commission, 2) . " Ar.";
        }
        $message .= " Frais de base : " . number_format($frais, 2) . " Ar.";

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

        $destinataires = [];
        $totalADebiter = 0;

        foreach ($numeros as $telephone) {
            if (!$prefixModel->isAllowed($telephone)) {
                return redirect()->back()->with('error', "Le numéro $telephone n'est pas valide.");
            }

            $prefixInfo = $prefixModel->where('prefixe', substr($telephone, 0, 3))->first();

            if ($prefixInfo['operateur_id'] != 1) {
                return redirect()->back()->with('error', "Le numéro $telephone appartient à un autre opérateur. Les transferts multiples sont limités aux numéros locaux.");
            }

            $destinataire = $clientModel->firstOrCreate($telephone);
            $frais = $baremeModel->calculerFrais($typeOperationId, $montantParDestinataire);

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

    public function epargne()
    {

        if (!session()->get('connecte')) {
            return redirect()->to(base_url('mobile-money/login'));
        }
        return view('mobile_money/client/epargne');
    }

    public function storeEpargne()
    {

        if (!session()->get('connecte')) {
            return redirect()->to(base_url('mobile-money/login'));
        }
        $clientModel = new ClientModel();

        $client =  $clientModel->find(session()->get('client_id'));
        if (!$client) {
            return redirect()->back()->with('error', 'Client introuvable.');
        }

        $epargne = $this->request->getPost('epargne');

        if (empty($epargne)) {
            return redirect()->back()->with('error', 'Tous les champs sont requis.');
        }

        if ($clientModel->update($client['id'], $epargne)) {
            return redirect()->to('/mobile-money/client/epargne')->with("success", "Epargne ajouter avec success. Valeur: " . $epargne);
        } else {
            return redirect()->back()->with("error", "Epargne non ajouter");
        }
    }
}
