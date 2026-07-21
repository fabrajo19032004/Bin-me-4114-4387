<?php
namespace App\Models;
use CodeIgniter\Model;

class ReductionModel extends Model
{
    protected $table = 'operateurs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nom', 'reductions_pourcentage'];
    protected $useTimestamps = false;
}