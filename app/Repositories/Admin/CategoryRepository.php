<?php

namespace App\Repositories\Admin;

use App\Models\Category;
use App\Models\User;
use App\Repositories\Interfaces\Admin\CategoryRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function fetchAll(): Category|Collection
    {
        return Category::all();
    }
}
