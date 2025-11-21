<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

use App\Models\Location;
use Illuminate\Database\Eloquent\Collection;

interface LocationInterface
{
    public function getAll(): Collection;
    public function findById(string $id): Location;
}

