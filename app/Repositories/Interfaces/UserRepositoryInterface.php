<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserRepositoryInterface
{
    public function create(array $data): User;

    public function findBy(string $column, mixed $value): User;

    public function updatePassword(User $user, string $password): User;
}
