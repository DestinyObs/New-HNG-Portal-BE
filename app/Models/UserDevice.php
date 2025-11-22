<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = [
        'user_id', 'name', 'last_activity_at'
    ];

    protected $casts = [
        'last_activity_at' => 'datetime',
    ];

}
