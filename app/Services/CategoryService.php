<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use Exception;

class CategoryService
{
    public function __construct(private CategoryRepository $repo) {}

    public function listCategories()
    {
        return $this->repo->all();
    }

    public function createCategory(array $data)
    {
        // Optional: protect against duplicate names
        if ($this->repo->all()->where('name', $data['name'])->first()) {
            throw new Exception("Category already exists", 422);
        }

        return $this->repo->create($data);
    }

    public function updateCategory(array $data, string $id)
    {
        $category = $this->repo->find($id);

        if (!$category) {
            throw new \Exception("Category not found", 404);
        }

        return $this->repo->update($data, $id);
    }

    public function deleteCategory(string $id)
{
    $category = $this->repo->find($id);

    if (!$category) {
        throw new Exception("Category not found", 404);
    }

    return $this->repo->delete($id);
}

}
