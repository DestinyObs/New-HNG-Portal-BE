<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Enums\Http;
use App\Repositories\Interfaces\Admin\JobLevelRepositoryInterface;
use App\Services\Interfaces\Admin\JobLevelServiceInterface;
use App\Traits\UploadFile;

class JobLevelService implements JobLevelServiceInterface
{
    use UploadFile;

    public function __construct(
        private readonly JobLevelRepositoryInterface $jobLevelRepository,
    ) {}

    public function getAllJobLevels(): object|array
    {
        try {
            $jobLevels = $this->jobLevelRepository->fetchAll();

            return (object) [
                'success' => true,
                'status' => Http::OK,
                'data' => $jobLevels,
                'message' => 'Job levels fetched successfully.',
            ];
        } catch (\Exception $e) {
            return (object) [
                'success' => false,
                'status' => Http::INTERNAL_SERVER_ERROR,
                'message' => 'An error occurred while fetching job levels.',
            ];
        }
    }
}