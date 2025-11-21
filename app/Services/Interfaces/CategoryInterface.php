<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryInterface
{
    public function getAll(): Collection;
    public function findById(string $id): Category;
}

