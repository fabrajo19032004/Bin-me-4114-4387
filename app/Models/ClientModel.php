<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table = 'clients';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['nom', 'telephone', 'solde', 'date_creation'];

    public function firstOrCreate(string $telephone): array
    {
        $client = $this->where('telephone', $telephone)->first();

        if ($client) {
            return $client;
        }

        $this->insert([
            'nom' => 'Client',
            'telephone' => $telephone,
            'solde' => 0,
        ]);

        return $this->where('telephone', $telephone)->first();
    }

    public function updateBalance(int $id, float $balance): bool
    {
        return (bool) $this->update($id, ['solde' => $balance]);
    }

    public function getBalance(int $id): float
    {
        $client = $this->find($id);

        return (float) ($client['solde'] ?? 0);
    }
}
