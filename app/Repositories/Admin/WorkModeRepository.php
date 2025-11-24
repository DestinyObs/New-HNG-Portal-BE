<?php

namespace App\Repositories\Admin;

use App\Models\WorkMode;
use App\Repositories\Interfaces\Admin\WorkModeRepositoryInterface;
use Illuminate\Support\Collection;

class WorkModeRepository implements WorkModeRepositoryInterface
{
    public function fetchAll(): WorkMode|Collection
    {
        return WorkMode::all();
    }
}
