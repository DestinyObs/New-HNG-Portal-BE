<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Company extends Model implements HasMedia
{
    use HasFactory, HasUuids, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'user_id',
        'logo_url',
        'name',
        'slug',
        'description',
        'industry',
        'company_size',
        'website_url',
        'state',
        'country',
        'is_verified',
        'official_email',
        'status',

        'tagline',
        'value_proposition',
        'why_talents_should_work_with_us',
        
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['user', 'media'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobs()
    {
        return $this->hasMany(JobListing::class);
    }


    public function applications()
    {
        return $this->hasManyThrough(
            Application::class,
            JobListing::class,
            'company_id',   // Foreign key on job_listings table
            'job_id',       // Foreign key on applications table
            'id',           // Local key on companies table
            'id'            // Local key on job_listings table
        );
    }
}
