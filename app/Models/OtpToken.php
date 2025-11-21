<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class OtpToken extends Model
{
    use HasUuids;

    protected $table = 'otp_tokens';

    protected $fillable = [
        'user_id',
        'hashed_token',
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    // Since primary key is UUID
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * Relationship: OTP belongs to a User (UUID)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
