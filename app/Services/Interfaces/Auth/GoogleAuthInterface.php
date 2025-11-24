<?php

namespace App\Services\Interfaces\Auth;

use Laravel\Socialite\Contracts\User as GoogleUser;

interface GoogleAuthInterface
{
    public function handle(GoogleUser $googleUser): array;

    public function handleToken(string $accessToken, ?string $role = null, ?string $companyName = null): array;
}
