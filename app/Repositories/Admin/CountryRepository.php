<?php

namespace App\Repositories\Admin;

use App\Models\Category;
use App\Models\Country;
use App\Models\User;
use App\Repositories\Interfaces\Admin\CountryRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class CountryRepository implements CountryRepositoryInterface
{
    public function fetchAll(): Country|Collection
    {
        return Country::all();
    }
}