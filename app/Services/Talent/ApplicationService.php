<?php

declare(strict_types=1);

namespace App\Services\Talent;

use App\Enums\Http;
use App\Http\Resources\UserResource;
use App\Models\Application;
use App\Models\User;
use App\Repositories\Interfaces\Talent\ApplicationRepositoryInterface;
use App\Repositories\Interfaces\Talent\JobRepositoryInterface;
use App\Repositories\Interfaces\Talent\ProfileRepositoryInterface;
use App\Services\Interfaces\Talent\ApplicationServiceInterface;
use App\Services\Interfaces\Talent\JobServiceInterface;
use App\Services\Interfaces\Talent\ProfileServiceInterface;
use App\Traits\UploadFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplicationService implements ApplicationServiceInterface
{
    use UploadFile;

    public function __construct(
        private readonly ApplicationRepositoryInterface $applicationRepository,
    ) {}


    public function createApplication(array $data, Request $request): object|array
    {
        try {
            //? Check if the job is active and published
            if (!$this->applicationRepository->checkIfJobCanBeApplied($data['job_id'])) {
                return (object) [
                    'success' => false,
                    'message' => 'This job is not open for applications.',
                    'status' => Http::INTERNAL_SERVER_ERROR,
                ];
            }

            DB::beginTransaction();

            $application = $this->applicationRepository->store($data);
            // dd($application);
            $uploadResume = $this->uploadResume($request, $application);

            if (!$uploadResume) {
                return (object) [
                    'success' => false,
                    'message' => 'Unable to upload resume',
                    'status' => Http::INTERNAL_SERVER_ERROR,
                ];
            }

            DB::commit();

            logger()->info("Application added successfully");

            return (object) [
                'success' => true,
                'application' => $application,
                'message' => 'Application added successfully',
                'status' => Http::OK,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error("Unable to add new application: " . $e->getMessage());

            return (object) [
                'success' => false,
                'message' => $e->getMessage(),
                'status' => Http::INTERNAL_SERVER_ERROR,
            ];
        }
    }


    public function listApplications(): object|array
    {
        try {
            $applications = $this->applicationRepository->getAll();

            return (object) [
                'success' => true,
                'applications' => $applications,
                'message' => 'Applications retrieved successfully',
                'status' => Http::OK,
            ];
        } catch (\Exception $e) {
            return (object) [
                'success' => false,
                'message' => $e->getMessage(),
                'status' => Http::INTERNAL_SERVER_ERROR,
            ];
        }
    }

    public function getSingleApplication(string $applicationId): object|array
    {
        try {
            $application = $this->applicationRepository->show($applicationId);

            return (object) [
                'success' => true,
                'application' => $application,
                'message' => 'Application retrieved successfully',
                'status' => Http::OK,
            ];
        } catch (\Exception $e) {
            return (object) [
                'success' => false,
                'message' => $e->getMessage(),
                'status' => Http::INTERNAL_SERVER_ERROR,
            ];
        }
    }

    public function withdrawApplication(string $applicationId): object|array
    {
        try {
            $application = $this->applicationRepository->withdraw($applicationId);

            return (object) [
                'success' => true,
                'application' => $application,
                'message' => 'Application withdrawn successfully',
                'status' => Http::OK,
            ];
        } catch (\Exception $e) {
            return (object) [
                'success' => false,
                'message' => $e->getMessage(),
                'status' => Http::INTERNAL_SERVER_ERROR,
            ];
        }
    }


    private function uploadResume(Request $request, Application $application)
    {
        if ($request->hasFile('resume')) {
            $file = $request->file('resume');

            // Generate random + timestamp filename
            $newName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $application->media->each->delete();        //? delete previous media file
            $application->addMedia($file)
                ->usingFileName($newName)
                ->toMediaCollection('resumes');

            return true;
        }

        return false;
    }
}