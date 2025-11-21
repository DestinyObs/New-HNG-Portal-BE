<?php

namespace App\Repositories\Interfaces\Admin;

use App\Models\Category;
use App\Models\User;
use App\Models\WorkMode;
use Illuminate\Support\Collection;

interface WorkModeRepositoryInterface
{
    public function fetchAll(): WorkMode|Collection;
}