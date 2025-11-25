<?php

declare(strict_types=1);

namespace App\Services\Interfaces\Admin;

interface WorkModeServiceInterface
{
    public function getAll(): object|array;
}
