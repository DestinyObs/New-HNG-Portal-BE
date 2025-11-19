<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Track;
use App\Repositories\Interfaces\TrackRepositoryInterface;
use App\Services\Interfaces\TrackInterface;
use Illuminate\Database\Eloquent\Collection;

class TrackService implements TrackInterface
{
    public function __construct(
        private readonly TrackRepositoryInterface $trackRepository,
    ) {}

    public function getAll(): Collection
    {
        return $this->trackRepository->getAll();
    }

    public function findById(string $id): Track
    {
        return $this->trackRepository->findById($id);
    }
}

