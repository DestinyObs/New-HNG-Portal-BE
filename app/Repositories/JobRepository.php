<?php

namespace App\Repositories;

use App\Models\JobListing;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class JobRepository
{
    public function findById(string $id): ?JobListing
    {
        return JobListing::with(['company', 'candidateLocation', 'category', 'jobType', 'track'])
            ->find($id);
    }

    public function findByIdForCompany(string $companyUuid, string $jobId): ?JobListing
    {
        return JobListing::where('company_id', $companyUuid)
            ->where('id', $jobId)
            ->with(['company', 'candidateLocation', 'category', 'jobType', 'track'])
            ->first();
    }

    public function listForCompany(string $companyUuid, int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = JobListing::where('company_id', $companyUuid)->with(['category', 'jobType', 'candidateLocation']);

        // apply simple filters if provided
        if (!empty($filters['title'])) {
            $query->where('title', 'like', '%'.$filters['title'].'%');
        }
        if (!empty($filters['job_type_id'])) {
            $query->where('job_type_id', $filters['job_type_id']);
        }
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function createForCompany(string $companyUuid, array $data): JobListing
    {
        $data['company_id'] = $companyUuid;
        
        return JobListing::create($data);
    }

    public function update(JobListing $job, array $data): JobListing
    {
        $job->fill($data);
        $job->save();
        return $job;
    }

    public function softDelete(JobListing $job): void
    {
        $job->delete();
    }

    public function restore(string $jobId): ?JobListing
    {
        $job = JobListing::withTrashed()->find($jobId);
        if ($job && $job->trashed()) {
            $job->restore();
        }
        return $job;
    }
}
