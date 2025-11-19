<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function getAll(): Collection
    {
        return $this->query()->get();
    }

    public function findById(string $id): Category
    {
        return $this->query()->findOrFail($id);
    }
}

