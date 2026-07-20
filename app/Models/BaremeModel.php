<?php

namespace App\Models;

use CodeIgniter\Model;

class BaremeModel extends Model
{
    protected $table            = 'baremes_frais';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['type_operation_id', 'montant_min', 'montant_max', 'frais'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $dateFormat       = 'datetime';

    // Récupère les barèmes avec le nom du type d'opération
    public function getBaremesWithType()
    {
        return $this->select('baremes_frais.*, types_operations.nom as type_nom')
                    ->join('types_operations', 'types_operations.id = baremes_frais.type_operation_id')
                    ->findAll();
    }
}