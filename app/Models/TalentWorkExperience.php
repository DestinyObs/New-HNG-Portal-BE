<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalentWorkExperience extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'company_name',
        'start_date',
        'end_date',
        'job_title',
        'description',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
