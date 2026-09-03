<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserRepository extends EloquentRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    public function findByRole(string $role): Collection
    {
        return $this->model->role($role)->get();
    }

    public function search(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function updatePassword(int $id, string $password): bool
    {
        $user = $this->find($id);
        if (!$user) {
            return false;
        }

        return $user->update(['password' => Hash::make($password)]);
    }

    public function updateStatus(int $id, bool $status): bool
    {
        $user = $this->find($id);
        if (!$user) {
            return false;
        }

        return $user->update(['is_active' => $status]);
    }

    public function assignRole(int $id, string $role): bool
    {
        $user = $this->find($id);
        if (!$user) {
            return false;
        }

        return $user->assignRole($role);
    }

    public function removeRole(int $id, string $role): bool
    {
        $user = $this->find($id);
        if (!$user) {
            return false;
        }

        return $user->removeRole($role);
    }

    public function syncRoles(int $id, array $roles): bool
    {
        $user = $this->find($id);
        if (!$user) {
            return false;
        }

        return $user->syncRoles($roles);
    }
}
