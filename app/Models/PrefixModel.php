<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixModel extends Model
{
    protected $table = 'prefixes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['prefixe', 'operateur_id'];

    public function isAllowed(string $telephone): bool
    {
        $prefix = substr($telephone, 0, 3);
        $row = $this->where('prefixe', $prefix)->first();

        return !empty($row);
    }
}
