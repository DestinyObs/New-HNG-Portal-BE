<?php

namespace App\Services\Interfaces\Auth;

interface GoogleAuthInterface
{
    public function handle(string $googleToken, ?string $role = null, ?string $companyName = null): array|\Exception;

}
