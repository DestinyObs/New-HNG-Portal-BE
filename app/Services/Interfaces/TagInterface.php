<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;

interface TagInterface
{
    public function getAll(): Collection;
    public function findById(string $id): Tag;
}

