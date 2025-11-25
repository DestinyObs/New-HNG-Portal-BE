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
    use HasFactory, SoftDeletes, HasUuids, InteractsWithMedia;

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
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobs()
    {
        return $this->hasMany(JobListing::class);
    }
}
