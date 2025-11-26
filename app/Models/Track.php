<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    use HasFactory, HasUuids;

    public function jobListings()
    {
        return $this->hasMany(JobListing::class, 'track_id', 'id');
    }
}