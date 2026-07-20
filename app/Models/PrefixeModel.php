<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeModel extends Model
{
    protected $table            = 'prefixes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['prefixe', 'operateur_id'];
    protected $useTimestamps = false;
    protected $createdField  = null;
    protected $updatedField  = null;
    protected $dateFormat       = 'datetime';
}
