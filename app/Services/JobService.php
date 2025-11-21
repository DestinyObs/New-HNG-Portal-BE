<?php

namespace App\Services;

use App\Models\JobListing;
use App\Repositories\JobRepository;
use App\Services\Interfaces\JobInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class JobService implements JobInterface
{
    protected JobRepository $repo;

    public function __construct(JobRepository $repo)
    {
        $this->repo = $repo;
    }

    /*
    |--------------------------------------------------------------------------
    | PUBLIC ENDPOINTS
    |--------------------------------------------------------------------------
    */

    public function list(array $filters)
    {
        $query = JobListing::query()
            ->with(['company', 'location', 'track', 'category', 'jobType']);

        if (!empty($filters['q'])) {
            $q = $filters['q'];
            $query->where(function (Builder $b) use ($q) {
                $b->where('title', 'LIKE', "%{$q}%")
                    ->orWhere('description', 'LIKE', "%{$q}%");
            });
        }

        if (!empty($filters['location_id'])) {
            $query->where('candidate_location_id', $filters['location_id']);
        } elseif (!empty($filters['location'])) {
            $query->whereHas('location', function ($q) use ($filters) {
                $q->where('name', 'LIKE', "%{$filters['location']}%");
            });
        }

        if (!empty($filters['job_type_id'])) {
            $query->where('job_type_id', $filters['job_type_id']);
        }

        if (!empty($filters['track_id'])) {
            $query->where('track_id', $filters['track_id']);
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

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
        return $job->load(['company', 'location', 'track', 'category', 'jobType', 'tags']);
    }

    public function related(JobListing $job): array
    {
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
        return $this->list(['q' => $query]);
    }

    /*
    |--------------------------------------------------------------------------
    | COMPANY (ADMIN) ENDPOINTS
    |--------------------------------------------------------------------------
    */

    public function listForCompany(string $companyUuid, array $params = [], int $perPage = 15): LengthAwarePaginator
    {
        $filters = [
            'title' => $params['title'] ?? null,
            'job_type_id' => $params['job_type_id'] ?? null,
            'category_id' => $params['category_id'] ?? null,
        ];

        return $this->repo->listForCompany($companyUuid, $perPage, $filters);
    }

    public function getForCompany(string $companyUuid, string $jobId): ?JobListing
    {
        return $this->repo->findByIdForCompany($companyUuid, $jobId);
    }

    public function create(string $companyUuid, array $data): JobListing
    {
        return $this->repo->createForCompany($companyUuid, $data);
    }

    public function updateForCompany(string $companyUuid, string $jobId, array $data): ?JobListing
    {
        $job = $this->getForCompany($companyUuid, $jobId);
        if (!$job) {
            return null;
        }

        return $this->repo->update($job, $data);
    }

    public function deleteForCompany(string $companyUuid, string $jobId): bool
    {
        $job = $this->getForCompany($companyUuid, $jobId);
        if (!$job) {
            return false;
        }
        $this->repo->softDelete($job);
        return true;
    }

    public function restore(string $jobId): ?JobListing
    {
        return $this->repo->restore($jobId);
    }
}
