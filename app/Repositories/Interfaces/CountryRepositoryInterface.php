<?php

namespace App\Repositories\Interfaces;

use App\Models\Country;
use Illuminate\Database\Eloquent\Collection;

interface CountryRepositoryInterface
{
    public function getAll(): Collection;
    public function findById(string $id): Country;
}

