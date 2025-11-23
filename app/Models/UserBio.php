<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBio extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'current_role',
        'bio',
        'track_id',
        'project_name',
        'project_url'
    ];
}
