<?php

namespace App\Repositories\Interfaces\Admin;

use App\Models\Track;
use Illuminate\Support\Collection;

interface TrackRepositoryInterface
{
    public function fetchAll(): Track|Collection;
}
