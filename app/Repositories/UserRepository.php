<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    public function getAll()
    {
        return;
    }

    public function create(array $data)
    {
        return;
    }

    public function update(User $user, array $data)
    {
       //
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }
}
