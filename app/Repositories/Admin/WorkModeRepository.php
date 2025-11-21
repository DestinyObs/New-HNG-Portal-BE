<?php

namespace App\Repositories\Admin;

use App\Models\Track;
use App\Models\User;
use App\Models\WorkMode;
use App\Repositories\Interfaces\Admin\TrackRepositoryInterface;
use App\Repositories\Interfaces\Admin\WorkModeRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class WorkModeRepository implements WorkModeRepositoryInterface
{
    public function fetchAll(): WorkMode|Collection
    {
        return WorkMode::all();
    }
}