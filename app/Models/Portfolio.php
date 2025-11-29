<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Portfolio extends Model implements HasMedia
{
    use HasFactory, HasUuids, InteractsWithMedia;



    protected $fillable = [
        'user_id',
        'banner_url',
        'title',
        'description',
        'link'
    ];


    // Since primary key is UUID
    protected $keyType = 'string';

    public $incrementing = false;

    protected $with = ['media'];

    public function registerMediaCollections(): void
    {
        // For media intended for Cloudinary
        // Ensure 'cloudinary' is your disk name in config/filesystems.php
        // $this->addMediaCollection('banner_image')
        //      ->useDisk('cloudinary'); 

        // For media intended for the public disk
        $this->addMediaCollection('banner_image')
             ->useDisk('public');
    }

    /**
     * Relationship: Portfolio belongs to a User (UUID)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
