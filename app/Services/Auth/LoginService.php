<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Services\Interfaces\Auth\LoginInterface;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class LoginService implements LoginInterface
{
    /**
     * Attempt to authenticate a user and return user data with token.
     *
     * @param  string  $guard
     * @return Collection
     *
     * @throws AuthenticationException
     */
    public function attempt(array $credentials)
    {
        if (!Auth::attempt($credentials)) {
            throw new AuthenticationException(__('auth.failed'));
        }

        $user = Auth::user();

        // Block login if email not verified
        if (!$user->hasVerifiedEmail()) {
            // Auth::logout();

            throw new AuthenticationException('Your email is not verified. Please verify your email to continue.');
        }

        // Load company if the user owns one
        if ($user->relationLoaded('company') === false) {
            $user->load('company');
        }

        return collect([
            'user'  => $user,
            'token' => $user->createToken('auth_token')->plainTextToken,
        ]);
    }
}
