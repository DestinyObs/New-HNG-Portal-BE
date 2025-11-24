<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Enums\Http;
use App\Repositories\Interfaces\Admin\WorkModeRepositoryInterface;
use App\Services\Interfaces\Admin\WorkModeServiceInterface;
use App\Traits\UploadFile;

class WorkModeService implements WorkModeServiceInterface
{
    use UploadFile;

    public function __construct(
        private readonly WorkModeRepositoryInterface $workModeRepository,
    ) {}

    public function getAll(): object|array
    {
        try {
            $categories = $this->workModeRepository->fetchAll();

            return (object) [
                'success' => true,
                'status' => Http::OK,
                'data' => $categories,
                'message' => 'Work mode fetched successfully.',
            ];
        } catch (\Exception $e) {
            return (object) [
                'success' => false,
                'status' => Http::INTERNAL_SERVER_ERROR,
                'message' => 'An error occurred while fetching work modes.',
            ];
        }
    }
}
