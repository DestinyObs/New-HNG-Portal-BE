<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Enums\Http;
use App\Repositories\Interfaces\Admin\TrackRepositoryInterface;
use App\Services\Interfaces\Admin\TrackServiceInterface;
use App\Traits\UploadFile;

class TrackService implements TrackServiceInterface
{
    use UploadFile;

    public function __construct(
        private readonly TrackRepositoryInterface $trackRepositoryInterface,
    ) {}

    public function getAll(): object|array
    {
        try {
            $locations = $this->trackRepositoryInterface->fetchAll();

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
