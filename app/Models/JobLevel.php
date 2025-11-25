<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class JobLevel extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'slug',
        'description'
    ];

    /**
     * A job level can belong to many job listings.
     */
    public function jobListings()
    {
        return $this->belongsTo(JobListing::class, 'job_listing_job_level');
    }
}