<?php

namespace App\Repositories\Interfaces\Admin;

use App\Models\Category;
use App\Models\State;
use App\Models\User;
use Illuminate\Support\Collection;

interface StateRepositoryInterface
{
    public function fetchAll(): State|Collection;
}