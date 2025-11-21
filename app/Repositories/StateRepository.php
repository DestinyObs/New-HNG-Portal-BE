<?php

namespace App\Repositories;

use App\Models\State;
use App\Repositories\Interfaces\StateRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class StateRepository extends BaseRepository implements StateRepositoryInterface
{
    public function __construct(State $model)
    {
        parent::__construct($model);
    }

    public function getAll(): Collection
    {
        return $this->query()->get();
    }

    public function findById(string $id): State
    {
        return $this->query()->findOrFail($id);
    }

    public function getByCountryId(string $countryId): Collection
    {
        return $this->query()->where('country_id', $countryId)->get();
    }
}

