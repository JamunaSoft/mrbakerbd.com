<?php

namespace App\Repositories\Interfaces;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface extends RepositoryInterface
{
    public function getRootCategories(): Collection;
    public function getChildren(int $parentId): Collection;
    public function getWithProducts(): Collection;
    public function findBySlug(string $slug): ?Category;
}
