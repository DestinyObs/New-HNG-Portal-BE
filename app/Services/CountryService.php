<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Country;
use App\Repositories\Interfaces\CountryRepositoryInterface;
use App\Services\Interfaces\CountryInterface;
use Illuminate\Database\Eloquent\Collection;

class CountryService implements CountryInterface
{
    public function __construct(
        private readonly CountryRepositoryInterface $countryRepository,
    ) {}

    public function getAll(): Collection
    {
        return $this->countryRepository->getAll();
    }

    public function findById(string $id): Country
    {
        return $this->countryRepository->findById($id);
    }
}

