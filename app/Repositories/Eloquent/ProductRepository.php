<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository extends EloquentRepository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function getFeatured(int $limit = 8): Collection
    {
        return $this->model
            ->where('is_featured', true)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getByCategory(int $categoryId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->where('category_id', $categoryId)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getBySlug(string $slug): ?Product
    {
        return $this->model
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }

    public function search(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getRelated(Product $product, int $limit = 4): Collection
    {
        return $this->model
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function updateStock(int $id, int $quantity): bool
    {
        $product = $this->find($id);
        if (!$product) {
            return false;
        }

        return $product->update(['stock' => $quantity]);
    }
}
