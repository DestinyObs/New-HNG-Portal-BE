<?php

declare(strict_types=1);

namespace App\Services\Interfaces\Talent;

use App\Models\User;
use Illuminate\Http\Request;

interface ProfileServiceInterface
{
    public function changePassword(User $user, string $currentPassword): object|array;

    public function updateProfilePhoto(User $user, Request $request): object|array;
}
