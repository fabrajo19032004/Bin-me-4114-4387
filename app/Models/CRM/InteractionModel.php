<?php

namespace App\Models\CRM;

use CodeIgniter\Model;

class InteractionModel extends Model
{
    protected $table      = 'interactions';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'client_id',
        'type',
        'description',
        'date'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    /**
     * Get interactions for a specific client.
     */
    public function getByClientId(int $clientId)
    {
        return $this->where('client_id', $clientId)
                    ->orderBy('date', 'DESC')
                    ->findAll();
    }
}
