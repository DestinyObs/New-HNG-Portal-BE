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

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function track()
    {
        return $this->belongsTo(Track::class, 'track_id');
    }

    public function jobType()
    {
        return $this->belongsTo(JobType::class, 'job_type_id');
    }

    public function candidateLocation()
    {
        return $this->belongsTo(Location::class, 'candidate_location_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

}