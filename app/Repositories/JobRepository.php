<?php

namespace App\Repositories;

use App\Enums\Status;
use App\Models\Company;
use App\Models\JobListing;
use App\Models\JobListingSkill;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class JobRepository
{
    public function findById(string $id): ?JobListing
    {
        return JobListing::with([
            'category',
            'states',
            'countries',
            'category',
            'jobType',
            'track',
            'skills',
        ])
            ->find($id);
    }

    public function findByIdForCompany(string $companyUuid, string $jobId): ?JobListing
    {
        return JobListing::where('company_id', $companyUuid)
            ->where('id', $jobId)
            ->with([
                'category',
                'states',
                'countries',
                'category',
                'jobType',
                'track',
                'skills',
            ])
            ->first();
    }

    public function listForCompany(string $companyUuid, int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = JobListing::where('company_id', $companyUuid)
            ->with([
                'category',
                'states',
                'countries',
                'category',
                'jobType',
                'track',
                'skills',
            ]);

        // apply simple filters if provided
        if (! empty($filters['title'])) {
            $query->where('title', 'like', '%'.$filters['title'].'%');
        }
        if (! empty($filters['job_type_id'])) {
            $query->where('job_type_id', $filters['job_type_id']);
        }
        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function checkIfCompanyIdExist(int|string $companyID): bool
    {
        return Company::where('id', $companyID)
            ->where('user_id', Auth::id())
            ->exists();
    }

    // public function createAndPushlishJob(string $companyUuid, array $data): JobListing
    // {
    //     $jobId = $data['job_id'] ?? null;

    //     $data['company_id'] = $companyUuid;
    //     $data['status'] = 'published'; // Ensure it's always a draft

    //     //? check if job id was included
    //     if (is_null($jobId)) {
    //         //? No job ID provided → create a new draft
    //         return JobListing::create($data);
    //     }

    //     return JobListing::create($data);
    // }

    public function createOrUpdateJob(string $companyUuid, array $data, bool $isDraft, $isPublish = false): JobListing
    {
        $jobId = $data['job_id'] ?? null;
        $data['publication_status'] = $isPublish ? 'published' : 'unpublished';

        // ? Always set the company ID to the logged-in company's UUID
        $data['company_id'] = $companyUuid;
        $data['status'] = $isDraft ? 'draft' : 'active'; // ? Ensure it's always a draft or active job

        // dd($data);

        // dd($data);
        if (is_null($jobId)) {
            // ? No job ID provided → create a new draft
            return JobListing::create($data);
        }

        // ? Job ID provided → try to find the draft belonging to the current user
        $job = JobListing::where('id', $jobId)
            ->where('company_id', $companyUuid)
            ->where('status', 'draft')
            ->first();

        if (! $job) {
            throw new \Exception(
                'The specified job does not exist or it is not longer a draft or you do not have permission to edit it.'
            );
        }

        // Update the existing draft
        $job->update($data);

        return $job;
    }

    public function addJobSkills(array $skills, int|string $jobId): void
    {
        foreach ($skills as $skillId) {
            // ? Skip if the skill is already attached to the job
            $exists = JobListingSkill::where('job_listing_id', $jobId)
                ->where('job_skill_id', $skillId)
                ->exists();

            if ($exists) {
                continue;
            }

            // ? Attach new skill
            JobListingSkill::create([
                'job_listing_id' => $jobId,
                'job_skill_id' => $skillId,
            ]);
        }
    }

    public function update(JobListing $job, array $data): JobListing
    {
        $job->fill($data);
        $job->save();

        return $job;
    }

    public function publish(JobListing $job, string $isPublish)
    {
        $job->publication_status = $isPublish;
        $job->save();

        return $job;
    }

    public function updateStatus(JobListing $job, string $isActive)
    {
        $job->status = $isActive;
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
