<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Enums\Http;
use App\Repositories\Interfaces\Admin\CategoryRepositoryInterface;
use App\Services\Interfaces\Admin\CategoryServiceInterface;
use App\Traits\UploadFile;

class CategoryService implements CategoryServiceInterface
{
    use UploadFile;

    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository,
    ) {}

    public function getAllCategories(): object|array
    {
        try {
            $categories = $this->categoryRepository->fetchAll();

            return (object) [
                'success' => true,
                'status' => Http::OK,
                'data' => $categories,
                'message' => 'Categories fetched successfully.',
            ];
        } catch (\Exception $e) {
            return (object) [
                'success' => false,
                'status' => Http::INTERNAL_SERVER_ERROR,
                'message' => 'An error occurred while fetching categories.',
            ];
        }
    }
}
