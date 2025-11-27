<?php

namespace App\Repositories;

use App\Enums\Status;
use App\Models\Application;
use App\Models\Company;
use App\Models\JobListing;
use App\Models\JobListingSkill;
use Illuminate\Database\Eloquent\Builder;
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
                'jobLevels'
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
                'jobLevels'
            ]);

        return $this->filterDatas($query, $filters, $perPage);
    }


    public function listDraftedJobs(string $companyUuid, int $perPage = 15, array $filters = [])
    {
        $query = JobListing::where('company_id', $companyUuid)
            ->where('status', 'draft')
            ->with([
                'category',
                'states',
                'countries',
                'category',
                'jobType',
                'track',
                'skills',
                'jobLevels',
            ]);

        return $this->filterDatas($query, $filters, $perPage);
    }


    private function filterDatas(Builder $query, array $filters, int $perPage): LengthAwarePaginator
    {
        // apply simple filters if provided
        if (!empty($filters['title'])) {
            $query->where('title', 'like', '%' . $filters['title'] . '%');
        }
        if (!empty($filters['job_type_id'])) {
            $query->where('job_type_id', $filters['job_type_id']);
        }
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['track_id'])) {
            $query->where('track_id', $filters['track_id']);
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

        // dd($data);
        // Update the existing draft
        $job->update($data);

        return $job;
    }

    // public function addJobSkills(array $skills, int|string $jobId): void
    // {
    //     foreach ($skills as $skillId) {
    //         // ? Skip if the skill is already attached to the job
    //         $exists = JobListingSkill::where('job_listing_id', $jobId)
    //             ->where('job_skill_id', $skillId)
    //             ->exists();

    //         if ($exists) {
    //             continue;
    //         }

    //         // ? Attach new skill
    //         JobListingSkill::create([
    //             'job_listing_id' => $jobId,
    //             'job_skill_id' => $skillId,
    //         ]);
    //     }
    // }

    public function addJobSkills(array $skills, string $jobId): void
    {
        $job = JobListing::findOrFail($jobId);

        // Sync the skills (job_skill_id column)
        $job->skills()->sync($skills);
    }

    public function addJobLevels(array $jobLevels, string $jobId)
    {
        $job = JobListing::findOrFail($jobId);

        // Sync the job levels (job_level_id column)
        $job->jobLevels()->sync($jobLevels);
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

        return $job->load([
            'applications.user',
            'category',
            'states',
            'countries',
            'category',
            'jobType',
            'track',
            'skills',
            'jobLevels'
        ]);
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


    public function getApplications(string $jobId): JobListing
    {
        return JobListing::query()
            ->with([
                'applications.user',
                'category',
                'states',
                'countries',
                'category',
                'jobType',
                'track',
                'skills',
                'jobLevels'
            ])
            ->findOrFail($jobId);
    }


    public function getSingleApplication(string $jobId, string $applicationId): Application
    {
        $job = JobListing::query()
            ->with([
                'applications.user',
                'category',
                'states',
                'countries',
                'jobType',
                'track',
                'skills',
                'jobLevels'
            ])
            ->findOrFail($jobId);

        // dd($job);

        // Get a single application from the loaded relationship
        $application = $job->applications()
            ->where('id', $applicationId)
            ->with(['user', 'job'])
            ->firstOrFail();

        // dd($application);

        return $application;
    }

    public function updateApplicationStatus(string $applicationId, string $status): ?Application
    {
        $application = Application::query()
            ->where('id', $applicationId)
            ->first();

        if (!$application) {
            return null;
        }

        $application->status = $status;
        $application->save();

        return $application->load(['user', 'job']); // Load relations if needed
    }
}