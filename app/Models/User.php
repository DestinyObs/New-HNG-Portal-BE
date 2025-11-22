<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasUuids, HasRoles;

    protected $fillable = [
        'firstname',
        'lastname',
        'othername',
        'email',
        'phone',
        'dob',
        'address_id',
        'status',
        'photo_url',
        'password',
        'google_id',
        'google_access_token',
        'google_refresh_token',
        'google_token_expires_at',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'google_access_token',
        'google_refresh_token'
    ];

    protected $with = [
        'roles', 'permissions'
    ];

    protected $casts = [
        'dob'         => 'date',
        'password'  => 'hashed',
        'google_token_expires_at' => 'datetime',
    ];

    // Relationships
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'user_skills', 'user_id', 'skill_id');
    }



    public function experiences()
    {
        return $this->hasMany(TalentWorkExperience::class, 'user_id');
    }

    public function verification()
    {
        return $this->hasOne(TalentVerification::class, 'user_id');
    }

    public function preferences()
    {
        return $this->hasMany(UserPreference::class, 'user_id');
    }

    public function company()
    {
        return $this->hasOne(Company::class, 'user_id');
    }

    public function jobs()
    {
        return $this->hasMany(JobListing::class, 'user_id');
    }

    public function devices()
    {
        return $this->hasMany(UserDevice::class);
    }
}
