<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class EloquentRepository implements RepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->orderBy('id', 'desc')->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }

    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    public function findBy(string $field, mixed $value): ?Model
    {
        return $this->model->where($field, $value)->first();
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Model
    {
        $model = $this->find($id);
        $model->update($data);
        return $model;
    }

    public function delete(int $id): bool
    {
        return $this->model->destroy($id) > 0;
    }

    public function getModel(): Model
    {
        return $this->model;
    }
}
