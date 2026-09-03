<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function findByEmail(string $email): ?User;
    public function findByRole(string $role): Collection;
    public function search(string $query, int $perPage = 15): LengthAwarePaginator;
    public function updatePassword(int $id, string $password): bool;
    public function updateStatus(int $id, bool $status): bool;
    public function assignRole(int $id, string $role): bool;
    public function removeRole(int $id, string $role): bool;
    public function syncRoles(int $id, array $roles): bool;
}
