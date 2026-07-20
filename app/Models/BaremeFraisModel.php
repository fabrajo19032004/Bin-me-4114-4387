<?php

namespace App\Models;

use CodeIgniter\Model;

class BaremeFraisModel extends Model
{
    protected $table = 'baremes_frais';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['type_operation_id', 'montant_min', 'montant_max', 'frais'];

    public function calculerFrais(int $typeOperationId, float $montant): float
    {
        $rows = $this->where('type_operation_id', $typeOperationId)
            ->orderBy('montant_min', 'ASC')
            ->findAll();

        foreach ($rows as $row) {
            if ($montant >= (float) $row['montant_min'] && $montant <= (float) $row['montant_max']) {
                return (float) $row['frais'];
            }
        }

        return 0.0;
    }
}
