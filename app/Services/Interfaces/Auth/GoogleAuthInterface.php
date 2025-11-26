<?php

namespace App\Services\Interfaces\Auth;
use App\Models\User;

interface GoogleAuthInterface
{
    public function handle(string $googleToken, ?string $role = null, ?string $companyName = null): array | User|\Exception;

}
