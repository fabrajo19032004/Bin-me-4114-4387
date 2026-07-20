<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['type_operation_id', 'expediteur_id', 'destinataire_id', 'montant', 'frais', 'date_transaction'];

    public function record(int $typeOperationId, ?int $expediteurId, ?int $destinataireId, float $montant, float $frais): bool
    {
        return (bool) $this->insert([
            'type_operation_id' => $typeOperationId,
            'expediteur_id' => $expediteurId,
            'destinataire_id' => $destinataireId,
            'montant' => $montant,
            'frais' => $frais,
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
