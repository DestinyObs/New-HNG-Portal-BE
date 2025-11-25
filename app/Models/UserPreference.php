<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'key',
        'value',
    ];

    protected $casts = [
        'value' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
