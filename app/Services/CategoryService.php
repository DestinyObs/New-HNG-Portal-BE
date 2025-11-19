<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Services\Interfaces\CategoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryService implements CategoryInterface
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository,
    ) {}

    public function getAll(): Collection
    {
        return $this->categoryRepository->getAll();
    }

    public function findById(string $id): Category
    {
        return $this->categoryRepository->findById($id);
    }
}

