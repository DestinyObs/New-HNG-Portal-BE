<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['slug', 'name'];

    public function jobListings()
    {
        return $this->belongsToMany(
            JobListing::class,
            'job_listing_skill',
            'job_skill_id',
            'job_listing_id'
        );
    }
}
