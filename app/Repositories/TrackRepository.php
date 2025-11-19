<?php

namespace App\Repositories;

use App\Models\Track;
use App\Repositories\Interfaces\TrackRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TrackRepository extends BaseRepository implements TrackRepositoryInterface
{
    public function __construct(Track $model)
    {
        parent::__construct($model);
    }

    public function getAll(): Collection
    {
        return $this->query()->get();
    }

    public function findById(string $id): Track
    {
        return $this->query()->findOrFail($id);
    }
}

