<?php

namespace App\Models;

use App\Models\Concerns\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasUuid, HasRoles;

    protected $fillable = [
        'uuid',
        'first_name',
        'last_name',
        'email',
        'phone',
        'dob',
        'location',
        'postal_code',
        'status',
        'is_verified',
        'bio',
        'photo_url',
        'min_salary',
        'max_salary',
        'password',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'dob'         => 'date',
        'is_verified' => 'boolean',
        'min_salary'  => 'integer',
        'max_salary'  => 'integer'
    ];

    // Relationships
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'talent_skills', 'user_id', 'skill_id');
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

    public function companies()
    {
        return $this->hasMany(Company::class, 'user_id');
    }

    public function jobs()
    {
        return $this->hasMany(JobListing::class, 'user_id');
    }
}
