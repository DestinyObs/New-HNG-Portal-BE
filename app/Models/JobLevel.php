<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobLevel extends Model
{
    use HasUuids, HasFactory;

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