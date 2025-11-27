<?php

namespace App\Models;

use App\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, HasUuids, Notifiable, SoftDeletes;

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
        'current_role',
        'email_verified_at',
    ];

    protected $hidden = ['password'];

    protected $with = [
        'roles',
        'permissions'
    ];

    protected $casts = [
        'dob' => 'date',
        'password' => 'hashed',
        'current_role' => RoleEnum::class
    ];


    // Relationships
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'user_skills', 'user_id', 'skill_id');
    }

    public function userSkills()
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

    // public function jobs()
    // {
    //     return $this->hasMany(JobListing::class, 'user_id');
    // }

    public function bio()
    {
        return $this->hasOne(UserBio::class, 'user_id');
    }


    public function bookmarks()
    {
        return $this->belongsToMany(JobListing::class, 'bookmarked_jobs', 'user_id', 'job_listing_id')
            ->withTimestamps();
    }

    // portfolios
    public function portfolios()
    {
        return $this->hasMany(Portfolio::class, 'user_id');
    }
}
