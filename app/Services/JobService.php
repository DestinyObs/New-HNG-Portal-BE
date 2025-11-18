<?php

namespace App\Services;

use App\Models\JobListing;
use App\Services\Interfaces\JobInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class JobService implements JobInterface
{
    public function list(array $filters)
    {
        $query = JobListing::query()
            ->with(['company', 'location', 'track', 'category', 'jobType']);

        // Search
        if (!empty($filters['q'])) {
            $q = $filters['q'];
            $query->where(function (Builder $b) use ($q) {
                $b->where('title', 'LIKE', "%{$q}%")
                    ->orWhere('description', 'LIKE', "%{$q}%");
            });
        }

        // candidate_location_id filter (accepts id or name)
        if (!empty($filters['location_id'])) {
            $query->where('candidate_location_id', $filters['location_id']);
        } elseif (!empty($filters['location'])) {
            // allow filtering by location name (joins)
            $query->whereHas('location', function ($q) use ($filters) {
                $q->where('name', 'LIKE', "%{$filters['location']}%");
            });
        }

        // job_type_id filter (accepts id)
        if (!empty($filters['job_type_id'])) {
            $query->where('job_type_id', $filters['job_type_id']);
        }

        // track_id filter
        if (!empty($filters['track_id'])) {
            $query->where('track_id', $filters['track_id']);
        }

        // category_id filter
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // price min/max example (optional)
        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        $perPage = isset($filters['per_page']) ? (int)$filters['per_page'] : 15;

        return $query->paginate($perPage);
    }

    public function find(JobListing $job): JobListing
    {
        // eager-load relationships for detail page
        return $job->load(['company', 'location', 'track', 'category', 'jobType', 'tags']);
    }

    public function related(JobListing $job): array
    {
        // Basic related logic: same job_type or same category (fallback to track)
        $query = JobListing::query()
            ->with(['company', 'jobType'])
            ->where('id', '!=', $job->id);

        if ($job->job_type_id) {
            $query->where('job_type_id', $job->job_type_id);
        } elseif ($job->category_id) {
            $query->where('category_id', $job->category_id);
        } elseif ($job->track_id) {
            $query->where('track_id', $job->track_id);
        }

        return $query->limit(6)->get()->toArray();
    }

    public function search(string $query)
    {
        // reuse list() search param to keep single source of truth
        return $this->list(['q' => $query]);
    }
}
