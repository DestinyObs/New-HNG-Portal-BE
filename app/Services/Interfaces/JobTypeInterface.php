<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

use App\Models\JobType;
use Illuminate\Database\Eloquent\Collection;

interface JobTypeInterface
{
    public function getAll(): Collection;
    public function findById(string $id): JobType;
}

