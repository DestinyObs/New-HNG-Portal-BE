<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobType extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
    ];

    public function jobListings()
    {
        return $this->hasMany(JobListing::class, 'job_type_id', 'id');
    }
}