<?php

namespace App\Models;

use App\Models\JobListing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory, SoftDeletes, HasUuids;
     use HasUuids, HasFactory;

    protected $fillable = [
        'name',
        'description',
        'industry',
        'website',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'logo_url',
        'size',
        'founded_year',
        'user_id' // owner/employer
    ];

    // Relationships
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(JobListing::class);
    }

}
