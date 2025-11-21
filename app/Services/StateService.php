<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\State;
use App\Repositories\Interfaces\StateRepositoryInterface;
use App\Services\Interfaces\StateInterface;
use Illuminate\Database\Eloquent\Collection;

class StateService implements StateInterface
{
    public function __construct(
        private readonly StateRepositoryInterface $stateRepository,
    ) {}

    public function getAll(): Collection
    {
        return $this->stateRepository->getAll();
    }

    public function findById(string $id): State
    {
        return $this->stateRepository->findById($id);
    }

    public function getByCountryId(string $countryId): Collection
    {
        return $this->stateRepository->getByCountryId($countryId);
    }
}

