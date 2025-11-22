<?php

namespace App\Services\Interfaces\Auth;

use Laravel\Socialite\Contracts\User as GoogleUser;

interface GoogleAuthInterface
{
    public function handle(GoogleUser $googleUser, string $role): array;
}
