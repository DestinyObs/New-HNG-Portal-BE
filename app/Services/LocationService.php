<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Location;
use App\Repositories\Interfaces\LocationRepositoryInterface;
use App\Services\Interfaces\LocationInterface;
use Illuminate\Database\Eloquent\Collection;

class LocationService implements LocationInterface
{
    public function __construct(
        private readonly LocationRepositoryInterface $locationRepository,
    ) {}

    public function getAll(): Collection
    {
        return $this->locationRepository->getAll();
    }

    public function findById(string $id): Location
    {
        return $this->locationRepository->findById($id);
    }
}

