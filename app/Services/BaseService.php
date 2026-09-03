<?php

namespace App\Services;

use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseService
{
    protected RepositoryInterface|CategoryRepositoryInterface $repository;

    public function __construct(RepositoryInterface|CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): Collection
    {
        return $this->repository->all();
    }

    public function find($id): ?Model
    {
        return $this->repository->find($id);
    }

    public function create(array $data): Model
    {
        return $this->repository->create($data);
    }

    public function update($id, array $data): bool
    {
        return $this->repository->update($id, $data);
    }

    public function delete($id): bool
    {
        return $this->repository->delete($id);
    }

    public function paginate($perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }
}
