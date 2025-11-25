<?php

namespace App\Repositories\Interfaces\Admin;

use App\Models\Category;
use Illuminate\Support\Collection;

interface LocationRepositoryInterface
{
    public function fetchAll(): Category|Collection;
}
