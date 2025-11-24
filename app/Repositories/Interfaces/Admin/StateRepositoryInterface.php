<?php

namespace App\Repositories\Interfaces\Admin;

use App\Models\State;
use Illuminate\Support\Collection;

interface StateRepositoryInterface
{
    public function fetchAll(): State|Collection;
}
