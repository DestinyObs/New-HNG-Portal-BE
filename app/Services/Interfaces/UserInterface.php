<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

use App\Models\User;

interface UserInterface
{
      public function create(array $data): User;
      public function logout(): void;

}
