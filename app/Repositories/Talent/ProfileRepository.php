<?php

namespace App\Repositories\Talent;

use App\Models\User;
use App\Repositories\Interfaces\Talent\ProfileRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class ProfileRepository implements ProfileRepositoryInterface
{
    public function updatePassword(User $user, string $password): User
    {
        $user->update([
            'password' => Hash::make($password),
        ]);

        return $user->refresh();
    }

    public function updateProfilePhoto(User $user, string $photoPath): User
    {
        $user->profile_url = $photoPath;
        $user->save();

        return $user->refresh();
    }
}
