<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

use App\Models\State;
use Illuminate\Database\Eloquent\Collection;

interface StateInterface
{
    public function getAll(): Collection;
    public function findById(string $id): State;
    public function getByCountryId(string $countryId): Collection;
}

