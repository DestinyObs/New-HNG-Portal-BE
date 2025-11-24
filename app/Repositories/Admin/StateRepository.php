<?php

namespace App\Repositories\Admin;

use App\Models\State;
use App\Repositories\Interfaces\Admin\StateRepositoryInterface;
use Illuminate\Support\Collection;

class StateRepository implements StateRepositoryInterface
{
    public function fetchAll(): State|Collection
    {
        return State::all();
    }
}
