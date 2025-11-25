<?php

namespace App\Repositories\Admin;

use App\Models\Track;
use App\Repositories\Interfaces\Admin\TrackRepositoryInterface;
use Illuminate\Support\Collection;

class TrackRepository implements TrackRepositoryInterface
{
    public function fetchAll(): Track|Collection
    {
        return Track::all();
    }
}
