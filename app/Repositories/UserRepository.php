<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    public function getAll(): Collection
    {
        return $this->query()->get();
    }

    public function create(array $data): User
    {
        return $this->query()->create($data);
    }

    public function findBy(string $column, mixed $value): User
    {
        return $this->query()->where($column, $value)->firstOrFail();
    }

    public function updatePassword(User $user, string $password): User
    {
        $user->update([
            'password' => $password
        ]);

        return $user->refresh();

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
