<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class WorkMode extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['slug', 'name'];

    /**
     * One-to-Many relationship:
     * A WorkMode can have many JobListings
     */
    public function jobListings()
    {
        return $this->hasMany(JobListing::class, 'work_mode_id', 'id');
    }
}