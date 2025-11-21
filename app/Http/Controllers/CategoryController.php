<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\Interfaces\CategoryInterface;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryInterface $categoryService
    ) {}

    /**
     * Display a listing of categories.
     */
    public function index()
    {
        $categories = $this->categoryService->getAll();
        return $this->successWithData($categories, 'Categories retrieved successfully');
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category)
    {
        return $this->successWithData($category, 'Category retrieved successfully');
    }
}

