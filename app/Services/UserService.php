<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class UserService extends BaseService
{
    public function __construct(UserRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->repository->findByEmail($email);
    }

    public function createUser(array $data): User
    {
        return $this->create($data) instanceof User ? $this->create($data) : throw new \RuntimeException('Invalid model type');
    }

    public function updateUser($id, array $data): bool
    {
        return $this->update($id, $data);
    }
    public function getUsersByRoles(array $roles)
    {
        return User::role($roles)
            ->select(['users.id', 'users.name', 'users.details', 'users.phone', 'users.email', 'users.photo', 'users.address', 'users.last_online', 'users.active'])
            ->with('roles:id,name')
            ->latest('users.id')
            ->paginate(25)
            ->withQueryString();
    }
}
