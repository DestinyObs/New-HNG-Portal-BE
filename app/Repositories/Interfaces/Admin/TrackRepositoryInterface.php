<?php

namespace App\Repositories\Interfaces\Admin;

use App\Models\Category;
use App\Models\Track;
use App\Models\User;
use Illuminate\Support\Collection;

interface TrackRepositoryInterface
{
    public function fetchAll(): Track|Collection;
}