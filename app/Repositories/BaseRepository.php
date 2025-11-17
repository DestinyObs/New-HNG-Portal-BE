<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
