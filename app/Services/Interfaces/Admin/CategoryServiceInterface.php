<?php

declare(strict_types=1);

namespace App\Services\Interfaces\Admin;

interface CategoryServiceInterface
{
    public function getAllCategories(): object|array;
}
