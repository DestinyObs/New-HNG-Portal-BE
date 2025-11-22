<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Services\CategoryService;
use App\Http\Controllers\Concerns\ApiResponse;
use App\Enums\Http;

class CategoryController extends Controller
{
    use ApiResponse;

    public function __construct(private CategoryService $service) {}

    public function index()
    {
        $categories = $this->service->listCategories();

        return $this->successWithData(
            $categories,
            'Categories retrieved successfully'
        );
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = $this->service->createCategory($request->validated());

        return $this->successWithData(
            $category,
            "Category created successfully",
            Http::CREATED
        );
    }

   public function update(UpdateCategoryRequest $request, $id)
{
    try {
        $category = $this->service->updateCategory($request->validated(), $id);

        return $this->successWithData(
            $category,
            "Category updated successfully"
        );
    } catch (\Exception $e) {

        if ($e->getCode() === 404) {
            return $this->notFound($e->getMessage());
        }

        return $this->error($e->getMessage());
    }
}


    public function destroy($id)
    {
        $this->service->deleteCategory($id);

        return $this->success(
            "Category deleted successfully"
        );
    }
}
