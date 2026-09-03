<?php

namespace App\Repositories\Interfaces;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface extends RepositoryInterface
{
    public function getFeatured(int $limit = 8): Collection;

    public function getByCategory(int $categoryId, int $perPage = 15): LengthAwarePaginator;

    public function getBySlug(string $slug): ?Product;

    public function search(string $query, int $perPage = 15): LengthAwarePaginator;

    public function getRelated(Product $product, int $limit = 4): Collection;

    public function updateStock(int $id, int $quantity): bool;
}
