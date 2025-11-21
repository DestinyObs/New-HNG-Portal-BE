<?php

namespace App\Repositories\Interfaces;

use App\Models\JobType;
use Illuminate\Database\Eloquent\Collection;

interface JobTypeRepositoryInterface
{
    public function getAll(): Collection;
    public function findById(string $id): JobType;
}

