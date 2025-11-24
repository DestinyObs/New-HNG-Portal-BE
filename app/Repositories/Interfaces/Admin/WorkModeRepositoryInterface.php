<?php

namespace App\Repositories\Interfaces\Admin;

use App\Models\WorkMode;
use Illuminate\Support\Collection;

interface WorkModeRepositoryInterface
{
    public function fetchAll(): WorkMode|Collection;
}
