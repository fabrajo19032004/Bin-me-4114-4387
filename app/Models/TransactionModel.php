<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['type_operation_id', 'expediteur_id', 'destinataire_id', 'montant', 'frais', 'est_vers_autre_operateur'];
    
    public function record(int $typeOperationId, ?int $expediteurId, ?int $destinataireId, float $montant, float $frais, int $estAutreOperateur = 0): bool
    {
        return (bool) $this->insert([
            'type_operation_id' => $typeOperationId,
            'expediteur_id' => $expediteurId,
            'destinataire_id' => $destinataireId,
            'montant' => $montant,
            'frais' => $frais,
            'est_vers_autre_operateur' => $estAutreOperateur, // ← cette ligne doit exister
        ]);
    }

    public function getByClient(int $clientId): array
    {
        return $this->where('expediteur_id', $clientId)
            ->orWhere('destinataire_id', $clientId)
            ->orderBy('date_transaction', 'DESC')
            ->findAll();
    }
}
