<?php

namespace App\Repositories\Admin;

use App\Models\Category;
use App\Repositories\Interfaces\Admin\CategoryRepositoryInterface;
use Illuminate\Support\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function fetchAll(): Category|Collection
    {
        return Category::all();
    }
}
