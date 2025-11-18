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
        $guard = 'web';
        // Attempt login with the specified guard
        if (!Auth::guard($guard)->attempt($credentials)) {
           throw new AuthenticationException(__('auth.failed'));
        }

        $user = Auth::guard($guard)->user();

        // Return user data with token
        return collect([
            'user' => $user,
            'token' => [
                'token' => $user->createToken('auth_token', [$guard])->plainTextToken
            ],
        ]);
    }
}
