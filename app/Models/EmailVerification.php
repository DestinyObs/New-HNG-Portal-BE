<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EmailVerification extends Model
{
    use HasUuids;

    protected $table = 'email_verifications';

    protected $fillable = [
        'user_id',
        'token',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    // Primary key uses UUID
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * Relationship: belongs to a User (UUID)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
