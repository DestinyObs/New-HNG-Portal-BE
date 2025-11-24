<?php

namespace App\Services\Interfaces\Auth;

interface GoogleAuthInterface
{
    /**
     * Handle Google OAuth login or signup.
     *
     * - If the user exists → login flow
     * - If the user does not exist → signup flow
     *
     * @param  array  $googleUserData  ['name' => '', 'email' => '', 'company_name' => 'optional']
     * @param  string|null  $role  'talent' or 'company' (required only for signup)
     * @return array ['user' => User, 'token' => string]
     */
    public function handle(array $googleUserData, ?string $role = null): array|\Exception;
}
