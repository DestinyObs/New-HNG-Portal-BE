<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory, HasUuids;

    public function jobListings()
    {
        return $this->hasMany(JobListing::class, 'state_id', 'id');
    }
}