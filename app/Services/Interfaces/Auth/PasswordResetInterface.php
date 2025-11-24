<?php

declare(strict_types=1);

namespace App\Services\Interfaces\Auth;

interface PasswordResetInterface
{
    public function sendResentLink(string $email): void;

    public function resetPassword(string $email, string $hash, string $password): void;
}
