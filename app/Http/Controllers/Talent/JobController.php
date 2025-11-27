<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use App\Http\Resources\Talent\JobCollection;
use App\Http\Resources\Talent\JobResource;
use App\Services\Interfaces\Talent\JobServiceInterface;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function __construct(
        private readonly JobServiceInterface $jobService,
    ) {}

    public function index(Request $request)
    {
        // dd($request->all());
        $perPage = (int) $request->query('per_page', 15);
        $response = $this->jobService->getJobs($request->only([
            'category',
            'job_level',
            'job_type',
            'work_mode'
        ]), $perPage);

        if ($response->success) {
            $jobs = JobResource::collection($response->jobs);

            return $this->paginated(
                $jobs,
                $response->message,
                $response->status,
            );
        }

        return $this->error(
            $response->message,
            $response->status,
        );
    }

    public function show(Request $request, string $jobId)
    {
        $response = $this->jobService->getJob($jobId);
        // dd($response->job);

        if ($response->success) {

            return $this->successWithData(
                new JobResource($response->job),
                $response->message,
                $response->status,
            );
        }

        return $this->error(
            $response->message,
            $response->status,
        );
    }

    public function saveJob(Request $request, string $jobUuid)
    {
        $response = $this->jobService->saveJob($jobUuid);
        // dd($response);

        if ($response->success) {

            return $this->successWithData(
                new JobResource($response->job),
                $response->message,
                $response->status,
            );
        }

        return $this->error(
            $response->message,
            $response->status,
        );
    }

    public function getSaveJobs(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $response = $this->jobService->getAllSavedJobs($request->only([
            'category',
            'job_level',
            'job_type',
            'work_mode'
        ]), $perPage);
        // dd($response);

        if ($response->success) {
            $jobs = JobResource::collection($response->jobs);

            return $this->paginated(
                $jobs,
                $response->message,
                $response->status,
            );
        }

        return $this->error(
            $response->message,
            $response->status,
        );
    }


    public function viewCompanyProfile(Request $request, string $companyId)
    {
        $response = $this->jobService->getCompany($companyId);
        // dd($response);

        if ($response->success) {

            return $this->successWithData(
                $response->company,
                $response->message,
                $response->status,
            );
        }

        return $this->error(
            $response->message,
            $response->status,
        );
    }
}