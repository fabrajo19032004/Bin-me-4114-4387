<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeOperationModel extends Model
{
    protected $table = 'types_operations';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['nom'];

    public function getIdByName(string $name): ?int
    {
        $row = $this->where('nom', $name)->first();

        return $row ? (int) $row['id'] : null;
    }
}
