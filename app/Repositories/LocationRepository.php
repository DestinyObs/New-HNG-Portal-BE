<?php

namespace App\Repositories;

use App\Models\Location;
use App\Repositories\Interfaces\LocationRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class LocationRepository extends BaseRepository implements LocationRepositoryInterface
{
    public function __construct(Location $model)
    {
        parent::__construct($model);
    }

    public function getAll(): Collection
    {
        return $this->query()->get();
    }

    public function findById(string $id): Location
    {
        return $this->query()->findOrFail($id);
    }
}

