<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['username', 'password', 'role', 'client_id', 'created_at'];

    public function authenticate(string $username, string $password): ?array
    {
        $user = $this->where('username', $username)->first();

        if (!$user) {
            return null;
        }

        if ($this->checkPassword($password, $user['password'] ?? '')) {
            return $user;
        }

        return null;
    }

    public function createClientUser(string $telephone, string $password): array
    {
        $existing = $this->where('username', $telephone)->first();
        if ($existing) {
            return $existing;
        }

        $clientModel = new ClientModel();
        $client = $clientModel->firstOrCreate($telephone);

        $data = [
            'username' => $telephone,
            'password' => $password,
            'role' => 'CLIENT',
            'client_id' => $client['id'],
        ];

        $this->insert($data);

        return $this->where('username', $telephone)->first();
    }

    private function checkPassword(string $entered, string $stored): bool
    {
        if ($stored === '') {
            return false;
        }

        return $entered === $stored || password_verify($entered, $stored);
    }
}
