<?php

namespace App\Repositories\Admin;

use App\Models\Track;
use App\Models\User;
use App\Repositories\Interfaces\Admin\TrackRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class TrackRepository implements TrackRepositoryInterface
{
    public function fetchAll(): Track|Collection
    {
        return Track::all();
    }
}
