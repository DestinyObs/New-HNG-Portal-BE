<?php

namespace App\Models;

use App\Models\JobListing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
        protected $fillable = [
        'job_listing_id',
        'user_id', // candidate_id
        'status',
        'cover_letter',
        'resume_url',
        'applied_at'
    ];

    protected $casts = [
        'applied_at' => 'datetime'
    ];

    // Relationships
    public function job(): BelongsTo
    {
        return $this->belongsTo(JobListing::class, 'job_id');
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
