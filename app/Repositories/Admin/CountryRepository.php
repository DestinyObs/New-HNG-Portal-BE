<?php

namespace App\Repositories\Admin;

use App\Models\Country;
use App\Repositories\Interfaces\Admin\CountryRepositoryInterface;
use Illuminate\Support\Collection;

class CountryRepository implements CountryRepositoryInterface
{
    public function fetchAll(): Country|Collection
    {
        return Country::all();
    }
}
