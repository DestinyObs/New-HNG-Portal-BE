<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Enums\Http;
use App\Repositories\Interfaces\Admin\LocationRepositoryInterface;
use App\Services\Interfaces\Admin\LocationServiceInterface;
use App\Traits\UploadFile;

class LocationService implements LocationServiceInterface
{
    use UploadFile;

    public function __construct(
        private readonly LocationRepositoryInterface $locationRepository,
    ) {}

    public function getAll(): object|array
    {
        try {
            $locations = $this->locationRepository->fetchAll();

            return (object) [
                'success' => true,
                'status' => Http::OK,
                'data' => $locations,
                'message' => 'Locations fetched successfully.',
            ];
        } catch (\Exception $e) {
            return (object) [
                'success' => false,
                'status' => Http::INTERNAL_SERVER_ERROR,
                'message' => 'An error occurred while fetching locations.',
            ];
        }
    }
}
