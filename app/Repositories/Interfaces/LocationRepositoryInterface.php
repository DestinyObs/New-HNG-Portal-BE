<?php

namespace App\Repositories\Interfaces;

use App\Models\Location;
use Illuminate\Database\Eloquent\Collection;

interface LocationRepositoryInterface
{
    public function getAll(): Collection;
    public function findById(string $id): Location;
}

