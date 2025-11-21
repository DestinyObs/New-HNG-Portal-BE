<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

use App\Models\Country;
use Illuminate\Database\Eloquent\Collection;

interface CountryInterface
{
    public function getAll(): Collection;
    public function findById(string $id): Country;
}

