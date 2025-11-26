<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookmarkedJob extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'bookmarked_jobs';

    protected $fillable = [
        'user_id',
        'job_listing_id',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobListing()
    {
        return $this->belongsTo(JobListing::class);
    }
}
