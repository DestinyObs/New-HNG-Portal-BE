<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalentVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document_url',
        'reasons',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
