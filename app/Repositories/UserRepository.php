<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getAll(): Collection
    {
        return $this->user->all();
    }

    public function create(array $data): User
    {
        return $this->user->create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user->refresh();
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }
}
