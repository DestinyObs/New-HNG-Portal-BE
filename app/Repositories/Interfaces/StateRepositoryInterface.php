<?php

namespace App\Repositories\Interfaces;

use App\Models\State;
use Illuminate\Database\Eloquent\Collection;

interface StateRepositoryInterface
{
    public function getAll(): Collection;
    public function findById(string $id): State;
    public function getByCountryId(string $countryId): Collection;
}

