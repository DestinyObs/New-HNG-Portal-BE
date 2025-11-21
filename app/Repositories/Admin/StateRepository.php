<?php

namespace App\Repositories\Admin;

use App\Models\Category;
use App\Models\Location;
use App\Models\State;
use App\Models\User;
use App\Repositories\Interfaces\Admin\StateRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class StateRepository implements StateRepositoryInterface
{
    public function fetchAll(): State|Collection
    {
        return State::all();
    }
}