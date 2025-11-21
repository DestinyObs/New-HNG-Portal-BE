<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobListing extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'title',
        'description',
        'acceptance_criteria',
        'candidate_location_id',
        'company_id',
        'price',
        'track_id',
        'category_id',
        'job_type_id',
    ];

    /*
     | Relationships
     */

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'candidate_location_id');
    }

    public function track()
    {
        return $this->belongsTo(Track::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function jobType()
    {
        return $this->belongsTo(JobType::class, 'job_type_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'job_tags');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'job_skills');
    }
}
