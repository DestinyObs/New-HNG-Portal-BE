<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class UserBio extends Model implements HasMedia
{
    use HasFactory, HasUuids, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'current_role',
        'bio',
        'track_id',
        'project_name',
        'project_url',
        'project_file_url',
        'state',
        'country',
        'experience',
        'available_status',
        'job_type_preference'
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

    public function track()
    {
        return $this->belongsTo(Track::class);
    }
}
