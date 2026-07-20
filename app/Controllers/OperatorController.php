<?php

namespace App\Controllers;

use App\Models\PrefixeModel;
use App\Models\TypeOperationModel;
use App\Models\BaremeModel;
use App\Models\ClientModel;

class OperatorController extends BaseController
{
    // -------------------- PRÉFIXES --------------------

    public function prefixes()
    {
        $model = new PrefixeModel();
        $data['prefixes'] = $model->findAll();
        return view('operator/prefixes', $data);
    }

    public function ajouterPrefixe()
    {
        $model = new PrefixeModel();
        $rules = [
            'prefixe' => 'required|is_unique[prefixes.prefixe]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model->save([
            'prefixe'       => $this->request->getPost('prefixe'),
            'operateur_id'  => 1, // À ajuster selon votre opérateur par défaut
        ]);

        return redirect()->to('/operator/prefixes')->with('success', 'Préfixe ajouté avec succès.');
    }

    public function supprimerPrefixe($id)
    {
        $model = new PrefixeModel();
        $model->delete($id);
        return redirect()->to('/operator/prefixes')->with('success', 'Préfixe supprimé.');
    }

    // -------------------- TYPES D'OPÉRATIONS --------------------

    public function types()
    {
        $model = new TypeOperationModel();
        $data['types'] = $model->findAll();
        return view('operator/types', $data);
    }

    public function ajouterType()
    {
        $model = new TypeOperationModel();
        $rules = [
            'nom' => 'required|is_unique[types_operations.nom]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model->save([
            'nom'         => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to('/operator/types')->with('success', 'Type ajouté.');
    }

    public function modifierType($id)
    {
        $model = new TypeOperationModel();
        $data['type'] = $model->find($id);
        if (!$data['type']) {
            return redirect()->to('/operator/types')->with('error', 'Type introuvable.');
        }
        return view('operator/type_edit', $data);
    }

    public function updateType($id)
    {
        $model = new TypeOperationModel();
        $rules = [
            'nom' => 'required|is_unique[types_operations.nom,id,' . $id . ']',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model->update($id, [
            'nom'         => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to('/operator/types')->with('success', 'Type mis à jour.');
    }

    public function supprimerType($id)
    {
        // Vérifier si le type est utilisé dans baremes_frais ou transactions
        $db = \Config\Database::connect();
        $count = $db->table('baremes_frais')->where('type_operation_id', $id)->countAllResults();
        if ($count > 0) {
            return redirect()->to('/operator/types')->with('error', 'Ce type est utilisé dans des barèmes, suppression impossible.');
        }

        $count2 = $db->table('transactions')->where('type_operation_id', $id)->countAllResults();
        if ($count2 > 0) {
            return redirect()->to('/operator/types')->with('error', 'Ce type est utilisé dans des transactions, suppression impossible.');
        }

        $model = new TypeOperationModel();
        $model->delete($id);
        return redirect()->to('/operator/types')->with('success', 'Type supprimé.');
    }

    // -------------------- BARÈMES DE FRAIS --------------------

    public function baremes()
    {
        $baremeModel = new BaremeModel();
        $typeModel   = new TypeOperationModel();

        $data['baremes'] = $baremeModel->getBaremesWithType();
        $data['types']   = $typeModel->findAll();

        return view('operator/baremes', $data);
    }

    public function ajouterBareme()
    {
        $rules = [
            'type_operation_id' => 'required|integer',
            'montant_min'       => 'required|numeric',
            'montant_max'       => 'permit_empty|numeric',
            'frais'             => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new BaremeModel();
        $model->save([
            'type_operation_id' => $this->request->getPost('type_operation_id'),
            'montant_min'       => $this->request->getPost('montant_min'),
            'montant_max'       => $this->request->getPost('montant_max') ?: null,
            'frais'             => $this->request->getPost('frais'),
        ]);

        return redirect()->to('/operator/baremes')->with('success', 'Barème ajouté.');
    }

    public function supprimerBareme($id)
    {
        $model = new BaremeModel();
        $model->delete($id);
        return redirect()->to('/operator/baremes')->with('success', 'Barème supprimé.');
    }

    // -------------------- SITUATION DES GAINS --------------------

    public function gains()
    {
        $db = \Config\Database::connect();

        $query = $db->query("
            SELECT
                t.nom AS type_operation,
                COALESCE(SUM(tr.frais), 0) AS total_frais
            FROM types_operations t
            LEFT JOIN transactions tr ON tr.type_operation_id = t.id
            WHERE t.nom IN ('Retrait', 'Transfert')
            GROUP BY t.id
        ");

        $data['gains'] = $query->getResultArray();

        // Si aucun résultat, on initialise à 0 pour chaque type
        if (empty($data['gains'])) {
            $data['gains'] = [
                ['type_operation' => 'Retrait', 'total_frais' => 0],
                ['type_operation' => 'Transfert', 'total_frais' => 0],
            ];
        }

        return view('operator/gains', $data);
    }

    // -------------------- SITUATION DES COMPTES CLIENTS --------------------

    public function comptesClients()
    {
        $model = new ClientModel();
        $data['clients'] = $model->findAll();
        return view('operator/comptes', $data);
    }
}