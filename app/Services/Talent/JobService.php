<?php

declare(strict_types=1);

namespace App\Services\Talent;

use Illuminate\Support\Facades\Auth;
use App\Enums\Http;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Interfaces\Talent\JobRepositoryInterface;
use App\Repositories\Interfaces\Talent\ProfileRepositoryInterface;
use App\Services\Interfaces\Talent\JobServiceInterface;
use App\Services\Interfaces\Talent\ProfileServiceInterface;
use App\Traits\UploadFile;
use Illuminate\Http\Request;

class JobService implements JobServiceInterface
{
    use UploadFile;

    public function __construct(
        private readonly JobRepositoryInterface $jobRepository
    ) {}


    public function getJobs(array $params, int $perPage): object|array
    {
        try {
            $jobs = $this->jobRepository->getJobs($params, $perPage);
            logger()->info("Jobs loaded successully");

            return (object) [
                'success' => true,
                'message' => 'Jobs retrieved successfully',
                'jobs' => $jobs,
                'status' => Http::OK,
            ];
        } catch (\Exception $e) {
            logger()->error("Unable to retrieve jobs: " . $e->getMessage());

            return (object) [
                'success' => false,
                'message' => 'Unable to retrieve jobs',
                'status' => Http::INTERNAL_SERVER_ERROR,
            ];
        }
    }


    public function getJob(string $jobUuid): object|array
    {
        try {
            $job = $this->jobRepository->getJob($jobUuid);

            if (!$job) {
                logger()->warning("Job not found: {$jobUuid}");

                return (object) [
                    'success' => false,
                    'message' => 'Job not found',
                    'status' => Http::NOT_FOUND,
                ];
            }

            logger()->info("Job retrieved successfully");

            return (object) [
                'success' => true,
                'message' => 'Job retrieved successfully',
                'job' => $job,
                'status' => Http::OK,
            ];
        } catch (\Exception $e) {
            logger()->error("Unable to retrieve job: " . $e->getMessage());

            return (object) [
                'success' => false,
                'message' => 'Unable to retrieve job',
                'status' => Http::INTERNAL_SERVER_ERROR,
            ];
        }
    }

    public function saveJob(string $jobUuid): object|array
    {
        try {
            $savedJob = $this->jobRepository->toggleSaveJob($jobUuid);
            // dd($savedJob['message']);
            logger()->info($savedJob['message']);

            return (object) [
                'success' => true,
                'message' => $savedJob['message'],
                'job' => $savedJob['job'],
                'status' => Http::OK,
            ];
        } catch (\Exception $e) {
            logger()->error("unable to save job: " . $e->getMessage());

            return (object) [
                'success' => false,
                'message' => 'Unable to perform operation',
                'status' => Http::INTERNAL_SERVER_ERROR,
            ];
        }
    }

    public function getAllSavedJobs(array $params, int $perPage): object|array
    {
        try {
            $savedJobs = $this->jobRepository->getSavedJobs($params, $perPage);

            logger()->info("Saved jobs retried successfully");

            return (object) [
                'success' => true,
                'message' => 'Saved jobs retrive successfully',
                'jobs' => $savedJobs,
                'status' => Http::OK,
            ];
        } catch (\Exception $e) {
            logger()->error("unable to retrieve saved jobs: " . $e->getMessage());

            return (object) [
                'success' => false,
                'message' => 'Unable to retrieve saved jobs',
                'status' => Http::INTERNAL_SERVER_ERROR,
            ];
        }
    }

    public function filters(): object|array
    {
        throw new \Exception('Not implemented');
    }

     public function dashboardAnalysis(): object|array
{
    try {
        $user = Auth::user();

        return (object)[
            'success' => true,
            'status' => 200,
            'message' => 'Dashboard analysis retrieved',
            'saved_jobs' => $user->bookmarks()->count(),
            'applications' => $user->applications()->count(),
        ];

    } catch (\Exception $e) {
        return (object)[
            'success' => false,
            'status' => 500,
            'message' => $e->getMessage(),
        ];
    }
}
}