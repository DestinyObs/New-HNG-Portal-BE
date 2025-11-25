<?php

namespace App\Repositories\Admin;

use App\Models\Category;
use App\Models\Location;
use App\Repositories\Interfaces\Admin\LocationRepositoryInterface;
use Illuminate\Support\Collection;

class LocationRepository implements LocationRepositoryInterface
{
    public function fetchAll(): Category|Collection
    {
        return Location::all();
    }
}
