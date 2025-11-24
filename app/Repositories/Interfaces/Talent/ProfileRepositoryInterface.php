<?php

namespace App\Repositories\Interfaces\Talent;

use App\Models\User;

interface ProfileRepositoryInterface
{
    public function updatePassword(User $user, string $password): User;

    public function updateProfilePhoto(User $user, string $photoPath): User;
}
