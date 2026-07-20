<?php

namespace App\Services;

use App\Models\ArchiveModel;

class ArchiveService
{
    protected ArchiveModel $model;

    public function __construct()
    {
        $this->model = new ArchiveModel();
    }

    public function getAll(): array
    {
        return $this->model->findAll();
    }

    public function getById($id): ?array
    {
        return $this->model->find($id);
    }

    public function create(array $data): int
    {
        return $this->model->insert($data);
    }

    public function update($id, array $data): bool
    {
        return $this->model->update($id, $data);
    }

    public function delete($id): bool
    {
        return $this->model->delete($id);
    }
}