<?php

namespace App\Repositories;

use App\Models\Country;
use App\Repositories\Interfaces\CountryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CountryRepository extends BaseRepository implements CountryRepositoryInterface
{
    public function __construct(Country $model)
    {
        parent::__construct($model);
    }

    public function getAll(): Collection
    {
        return $this->query()->get();
    }

    public function findById(string $id): Country
    {
        return $this->query()->findOrFail($id);
    }
}

