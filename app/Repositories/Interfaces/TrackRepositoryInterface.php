<?php

namespace App\Repositories\Interfaces;

use App\Models\Track;
use Illuminate\Database\Eloquent\Collection;

interface TrackRepositoryInterface
{
    public function getAll(): Collection;
    public function findById(string $id): Track;
}

