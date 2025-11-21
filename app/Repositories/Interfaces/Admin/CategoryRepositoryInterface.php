<?php

namespace App\Repositories\Interfaces\Admin;

use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Collection;

interface CategoryRepositoryInterface
{
    public function fetchAll(): Category|Collection;
}