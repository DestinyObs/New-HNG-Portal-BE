<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;

class AuthResponseService
{
    /**
     * Create a consistent auth response payload.
     * Returns an array with raw `user`, `token` and `is_new_user`.
     */
    public function create(User $user, string $token, bool $isNewUser): array
    {
        return [
            'user' => $user,
            'token' => $token,
            'is_new_user' => $isNewUser,
        ];
    }
}
