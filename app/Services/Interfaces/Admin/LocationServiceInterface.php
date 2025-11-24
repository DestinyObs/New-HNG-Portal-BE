<?php

declare(strict_types=1);

namespace App\Services\Interfaces\Admin;

interface LocationServiceInterface
{
    public function getAll(): object|array;
}
