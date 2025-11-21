<?php

namespace App\Repositories\Interfaces\Admin;

use App\Models\Country;
use Illuminate\Support\Collection;

interface CountryRepositoryInterface
{
    public function fetchAll(): Country|Collection;
}
