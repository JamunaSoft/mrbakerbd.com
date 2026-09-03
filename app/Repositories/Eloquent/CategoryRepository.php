<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        $key = 'paginate_' . $perPage;
        $this->addCacheKey($key);
        return $this->remember($key, function () use ($perPage) {
            return $this->optimizeQuery($this->model->query())
                ->withCount('products')
                ->paginate($perPage);
        });
    }

    public function find(int $id): ?Category
    {
        $key = 'find_' . $id;
        $this->addCacheKey($key);
        return $this->remember($key, function () use ($id) {
            return $this->optimizeQuery($this->model->query())
                ->with('products')
                ->find($id);
        });
    }

    public function create(array $data): Category
    {
        $category = $this->model->create($data);
        $this->flush();
        return $category;
    }

    public function update(int $id, array $data): Category
    {
        $category = $this->find($id);
        if ($category) {
            $category->update($data);
            $this->forget('find_' . $id);
            $this->flush();
        }
        return $category;
    }

    public function delete(int $id): bool
    {
        $category = $this->find($id);
        if ($category) {
            $result = $category->delete();
            $this->forget('find_' . $id);
            $this->flush();
            return $result;
        }
        return false;
    }

    public function getActive(): array
    {
        return $this->remember('active', function () {
            return $this->optimizeQuery($this->model->query())
                ->where('status', true)
                ->get()
                ->toArray();
        });
    }

    public function getRootCategories(): Collection
    {
        return $this->model->whereNull('parent_id')->orderBy('position')->get();
    }

    public function getChildren(int $parentId): Collection
    {
        return $this->model->where('parent_id', $parentId)->orderBy('position')->get();
    }

    public function getWithProducts(): Collection
    {
        return $this->model->with('products')->get();
    }

    public function findBySlug(string $slug): ?Category
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function all(): Collection
    {
        $key = 'all';
        $this->addCacheKey($key);
        return $this->remember($key, function () {
            return $this->optimizeQuery($this->model->query())
                ->with('products')
                ->get();
        });
    }

    public function findBy(string $field, mixed $value): ?Category
    {
        return $this->remember("findBy_{$field}_{$value}", function () use ($field, $value) {
            return $this->optimizeQuery($this->model->query())
                ->where($field, $value)
                ->first();
        });
    }

    public function getModel(): Category
    {
        return $this->model;
    }
}
