<?php

namespace App\Repositories\Interfaces\Admin;

use App\Models\Category;
use App\Models\JobLevel;
use Illuminate\Support\Collection;

interface JobLevelRepositoryInterface
{
    public function fetchAll(): JobLevel|Collection;
}