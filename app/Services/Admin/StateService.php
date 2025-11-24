<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Enums\Http;
use App\Repositories\Interfaces\Admin\StateRepositoryInterface;
use App\Services\Interfaces\Admin\StateServiceInterface;
use App\Traits\UploadFile;

class StateService implements StateServiceInterface
{
    use UploadFile;

    public function __construct(
        private readonly StateRepositoryInterface $stateRepository,
    ) {}

    public function getAll(): object|array
    {
        try {
            $states = $this->stateRepository->fetchAll();

            return (object) [
                'success' => true,
                'status' => Http::OK,
                'data' => $states,
                'message' => 'states fetched successfully.',
            ];
        } catch (\Exception $e) {
            return (object) [
                'success' => false,
                'status' => Http::INTERNAL_SERVER_ERROR,
                'message' => 'An error occurred while fetching states.',
            ];
        }
    }
}
