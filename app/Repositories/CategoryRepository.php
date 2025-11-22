<?php
namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public function all()
    {
        return Category::all();
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(array $data, string $id): Category
    {
        $category = Category::findOrFail($id);
        $category->update($data);
        return $category;
    }

    public function delete(string $id): bool
    {
        return Category::destroy($id) > 0;
    }

    public function find(string $id): ?Category
    {
        return Category::find($id);
    }
}
