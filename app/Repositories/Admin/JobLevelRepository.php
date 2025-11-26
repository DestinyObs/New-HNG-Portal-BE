<?php

namespace App\Repositories\Admin;

use App\Models\JobLevel;
use App\Repositories\Interfaces\Admin\JobLevelRepositoryInterface;
use Illuminate\Support\Collection;

class JobLevelRepository implements JobLevelRepositoryInterface
{
    public function fetchAll(): JobLevel|Collection
    {
        return JobLevel::all();
    }
}