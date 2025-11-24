<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

interface UserInterface
{
    public function create(array $data): array|\Exception;

    public function logout(): void;
}
