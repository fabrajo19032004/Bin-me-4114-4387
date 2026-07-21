<?php

namespace App\Controllers;

use App\Models\BaremeFraisModel;
use App\Models\ClientModel;
use App\Models\PrefixModel;
use App\Models\TransactionModel;
use App\Models\TypeOperationModel;
use App\Models\OperateurModel;
use App\Models\ReductionModel;

class OperatorMobileMoneyController extends MobileMoneyController
{
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
        $reductionModel = new ReductionModel();

        $clients = $clientModel->findAll();
        $transactions = $transactionModel->findAll();
        $prefixes = $prefixModel->orderBy('prefixe', 'ASC')->findAll();
        $operations = $typeOperationModel->orderBy('nom', 'ASC')->findAll();
        $fees = $feeModel->orderBy('type_operation_id', 'ASC')->orderBy('montant_min', 'ASC')->findAll();
        $operateurs = $operateurModel->findAll();

        $typeTransfert = $typeOperationModel->where('nom', 'Transfert')->first();
        $typeTransfertId = $typeTransfert ? $typeTransfert['id'] : null;

        return view('mobile_money/operator/overview_page', [
            'clients' => $clients,
            'transactions' => $transactions,
            'prefixes' => $prefixes,
            'operations' => $operations,
            'fees' => $fees,
            'operateurs' => $operateurs,
            'typeTransfertId' => $typeTransfertId,
            'title' => 'Vue d’ensemble opérateur',
            'role' => 'operator',
            'roleLabel' => 'Opérateur',
            'page' => 'dashboard',
        ]);
    }

    public function operatorPrefixes()
    {
        $redirect = $this->ensureOperator();
        if ($redirect !== null) {
            return $redirect;
        }

        $this->initializeDatabaseFromSql();

        $prefixModel = new PrefixModel();
        $operateurModel = new OperateurModel();
        $prefixes = $prefixModel->orderBy('prefixe', 'ASC')->findAll();
        $operateurs = $operateurModel->findAll();
       

        return view('mobile_money/operator/prefixes_page', [
            'prefixes' => $prefixes,
            'operateurs' => $operateurs,
            'title' => 'Préfixes',
            'role' => 'operator',
            'roleLabel' => 'Opérateur',
            'page' => 'prefixes',
        ]);
    }

    public function operatorOperations()
    {
        $redirect = $this->ensureOperator();
        if ($redirect !== null) {
            return $redirect;
        }

        $this->initializeDatabaseFromSql();

        $typeOperationModel = new TypeOperationModel();
        $operations = $typeOperationModel->orderBy('nom', 'ASC')->findAll();

        return view('mobile_money/operator/operations_page', [
            'operations' => $operations,
            'title' => 'Types d’opérations',
            'role' => 'operator',
            'roleLabel' => 'Opérateur',
            'page' => 'operations',
        ]);
    }

    public function operatorFees()
    {
        $redirect = $this->ensureOperator();
        if ($redirect !== null) {
            return $redirect;
        }

        $this->initializeDatabaseFromSql();

        $feeModel = new BaremeFraisModel();
        $fees = $feeModel->orderBy('type_operation_id', 'ASC')->orderBy('montant_min', 'ASC')->findAll();

        return view('mobile_money/operator/fees_page', [
            'fees' => $fees,
            'title' => 'Barèmes de frais',
            'role' => 'operator',
            'roleLabel' => 'Opérateur',
            'page' => 'fees',
        ]);
    }

    public function operatorClients()
    {
        $redirect = $this->ensureOperator();
        if ($redirect !== null) {
            return $redirect;
        }

        $this->initializeDatabaseFromSql();

        $clientModel = new ClientModel();
        $clients = $clientModel->findAll();

        return view('mobile_money/operator/clients_page', [
            'clients' => $clients,
            'title' => 'Clients',
            'role' => 'operator',
            'roleLabel' => 'Opérateur',
            'page' => 'clients',
        ]);
    }

    public function operatorTransactions()
    {
        $redirect = $this->ensureOperator();
        if ($redirect !== null) {
            return $redirect;
        }

        $this->initializeDatabaseFromSql();

        $transactionModel = new TransactionModel();
        $typeOperationModel = new TypeOperationModel();
        $transactions = $transactionModel->findAll();
        $typeTransfertId = $this->optional($typeOperationModel->where('nom', 'Transfert')->first())['id'] ?? null;

        return view('mobile_money/operator/transactions_page', [
            'transactions' => $transactions,
            'typeTransfertId' => $typeTransfertId,
            'title' => 'Transactions',
            'sectionTitle' => 'Transactions',
            'sectionSubtitle' => 'Historique global',
            'role' => 'operator',
            'roleLabel' => 'Opérateur',
            'page' => 'transactions',
        ]);
    }

    public function operatorHistoryInternal()
    {
        $redirect = $this->ensureOperator();
        if ($redirect !== null) {
            return $redirect;
        }

        $this->initializeDatabaseFromSql();

        $transactionModel = new TransactionModel();
        $typeOperationModel = new TypeOperationModel();
        $transactions = $transactionModel->where('est_vers_autre_operateur', 0)
            ->orderBy('date_transaction', 'DESC')
            ->findAll();
        $typeTransfertId = $this->optional($typeOperationModel->where('nom', 'Transfert')->first())['id'] ?? null;

        return view('mobile_money/operator/transactions_page', [
            'transactions' => $transactions,
            'typeTransfertId' => $typeTransfertId,
            'title' => 'Historique interne',
            'sectionTitle' => 'Historique interne',
            'sectionSubtitle' => 'Transactions vers le même opérateur',
            'role' => 'operator',
            'roleLabel' => 'Opérateur',
            'page' => 'history-internal',
        ]);
    }

    public function operatorHistoryExternal()
    {
        $redirect = $this->ensureOperator();
        if ($redirect !== null) {
            return $redirect;
        }

        $this->initializeDatabaseFromSql();

        $transactionModel = new TransactionModel();
        $typeOperationModel = new TypeOperationModel();
        $transactions = $transactionModel->where('est_vers_autre_operateur', 1)
            ->orderBy('date_transaction', 'DESC')
            ->findAll();
        $typeTransfertId = $this->optional($typeOperationModel->where('nom', 'Transfert')->first())['id'] ?? null;

        return view('mobile_money/operator/transactions_page', [
            'transactions' => $transactions,
            'typeTransfertId' => $typeTransfertId,
            'title' => 'Historique externe',
            'sectionTitle' => 'Historique externe',
            'sectionSubtitle' => 'Transactions vers un autre opérateur',
            'role' => 'operator',
            'roleLabel' => 'Opérateur',
            'page' => 'history-external',
        ]);
    }

    public function operatorGains()
    {
        $redirect = $this->ensureOperator();
        if ($redirect !== null) {
            return $redirect;
        }

        $this->initializeDatabaseFromSql();

        $transactionModel = new TransactionModel();
        $typeOperationModel = new TypeOperationModel();
        $transactions = $transactionModel->findAll();
        $typeTransfertId = $this->optional($typeOperationModel->where('nom', 'Transfert')->first())['id'] ?? null;

        return view('mobile_money/operator/gains_page', [
            'transactions' => $transactions,
            'typeTransfertId' => $typeTransfertId,
            'title' => 'Gains',
            'role' => 'operator',
            'roleLabel' => 'Opérateur',
            'page' => 'gains',
        ]);
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

     public function saveReductions()
    {
        $redirect = $this->ensureOperator();
        if ($redirect !== null) {
            return $redirect;
        }

        $id = (int) $this->request->getPost('id');
        $reductions = (float) $this->request->getPost('reductions_pourcentage');

        $reductionModel = new ReductionModel();
        $operateurModel->update($id, ['reductions_pourcentage' => $reductions]);

        return redirect()->back()->with('success', 'Reduction mise à jour.');
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
}
