<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobListingSkill extends Model
{
    protected $table = 'job_listing_skill';

    public $timestamps = true; // timestamps exist

    protected $fillable = [
        'job_listing_id',
        'job_skill_id',
    ];

    public function jobListing()
    {
        return $this->belongsTo(JobListing::class, 'job_listing_id', 'id');
    }

    public function jobSkill()
    {
        return $this->belongsTo(Skill::class, 'job_skill_id', 'id');
    }
}
