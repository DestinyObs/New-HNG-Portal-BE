<?php

declare(strict_types=1);

namespace App\Services\Interfaces\Auth;

interface LoginInterface
{
     public function attempt(array $credentials);
}
