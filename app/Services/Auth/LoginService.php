<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Services\Interfaces\Auth\LoginInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;

class LoginService implements LoginInterface
{
    /**
     * Attempt to authenticate a user and return user data with token.
     *
     * @param array $credentials
     * @param string $guard
     * @return Collection
     * @throws AuthenticationException
     */
    public function attempt(array $credentials)
    {
        if (!Auth::attempt($credentials)) {
           throw new AuthenticationException(__('auth.failed'));
        }

        $user = Auth::user();

        if ($user->has('company')) {
            $user->load('company');
        }

        return collect([
            'user' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken
        ]);
    }
}
