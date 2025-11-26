<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    public function __construct(
        protected readonly Model $model
    ) {}

    protected function query(): Builder
    {
        return $this->model->query();
    }
}
