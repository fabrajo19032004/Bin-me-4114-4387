<?php

namespace App\Models\CRM;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table      = 'clients';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'nom',
        'telephone',
        'email',
        'adresse',
        'region',
        'district',
        'statut',
        'date_creation'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    /**
     * Search, filter and sort clients with pagination.
     */
    public function getFilteredClients(array $filters = [], string $search = '', string $sortBy = 'nom', string $sortOrder = 'ASC')
    {
        $builder = $this->builder();

        // Search by name, phone or email
        if (!empty($search)) {
            $builder->groupStart()
                    ->like('nom', $search)
                    ->orLike('telephone', $search)
                    ->orLike('email', $search)
                    ->groupEnd();
        }

        // Multi-criteria filters
        if (!empty($filters['region'])) {
            $builder->where('region', $filters['region']);
        }
        if (!empty($filters['district'])) {
            $builder->where('district', $filters['district']);
        }
        if (!empty($filters['statut'])) {
            $builder->where('statut', $filters['statut']);
        }
        if (!empty($filters['date_creation'])) {
            $builder->where('date_creation', $filters['date_creation']);
        }

        // Sorting
        $allowedSortFields = ['nom', 'date_creation', 'created_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $builder->orderBy($sortBy, $sortOrder);
        } else {
            $builder->orderBy('nom', 'ASC');
        }

        return $this;
    }
}
