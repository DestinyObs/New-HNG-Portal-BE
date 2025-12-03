<?php

namespace App\Http\Controllers\Employer;

use App\Enums\Http;
use App\Http\Controllers\Controller;
use App\Http\Requests\Employer\StoreDraftJobRequest;
use App\Http\Requests\Employer\StoreJobRequest;
use App\Http\Requests\Employer\UpdateJobRequest;
use App\Http\Resources\Employer\JobListingResource;
use App\Http\Resources\Talent\ApplicationResource;
use App\Http\Resources\Talent\JobResource;
use App\Services\JobService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobController extends Controller
{
    protected JobService $service;

    public function __construct(JobService $service)
    {
        $this->service = $service;
    }

    /**
     * GET /employer/company/{uuid}/jobs
     */
    public function index(Request $request, $uuid): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $result = $this->service->listForCompany($uuid, $request->only(
            ['title', 'job_type_id', 'category_id', 'track_id', 'sort_by']
        ), $perPage);

        return $this->successWithData(
            $result,
            "Jobs retrieved successfully.",
            Http::OK,
        );
    }


    /**
     * GET /emplyer/company/{uuid}/jobs/draft
     */

    public function listDraftedJobs(Request $request, string $companyUuid)
    {
        // dd($request->all());
        $perPage = (int) $request->query('per_page', 15);
        $response = $this->service->listDraftedJobs($companyUuid, $request->only(
            ['title', 'job_type_id', 'category_id', 'track_id', 'sort_by']
        ), $perPage);

        // return response when passed
        if ($response->status) {
            return $this->successWithData(
                $response->data,
                $response->message,
                $response->status
            );
        }

        return $this->error(
            $response->message,
            $response->status
        );
    }

    /**
     * POST /employer/company/{uuid}/jobs
     */
    public function storePublishJob(StoreJobRequest $request, string|int $companyId): JsonResponse
    {
        // dd($request->all()['skills']);

        $response = $this->service->createOrUpdate(
            $companyId,
            $request->validated(),
            false,
            true
        );

        // ? case when job is added successfully
        if ($response->success) {
            return $this->successWithData(
                $response->data,
                $response->message,
                $response->status
            );
        }

        // ? handle error messages
        return $this->error(
            $response->message,
            $response->status
        );
    }

    public function draft(StoreDraftJobRequest $request, string|int $companyId): JsonResponse
    {
        // dd($request->all()['skills']);

        $response = $this->service->createOrUpdate(
            $companyId,
            $request->validated(),
            true,
            false,
        );

        // ? case when job is added successfully
        if ($response->success) {
            return $this->successWithData(
                $response->data,
                $response->message,
                $response->status
            );
        }

        // ? handle error messages
        return $this->error(
            $response->message,
            $response->status
        );
    }

    //   GET /employer/company/{uuid}/jobs/{job_id}

    public function show($uuid, $job_id): JsonResponse
    {
        $job = $this->service->getForCompany($uuid, $job_id);

        // dd($job);
        if (! $job) {
            return $this->error('Job not found', Http::NOT_FOUND);
        }

        return $this->successWithData($job, 'Job retrieved successfully');
    }

    //   PUT /employer/company/{uuid}/jobs/{job_id}

    public function update(UpdateJobRequest $request, $uuid, $job_id): JsonResponse
    {
        // dd($request->validated());

        $updated = $this->service->updateForCompany(
            $uuid,
            $job_id,
            $request->validated()
        );

        if (! $updated) {
            return $this->error(
                'Job not found or not editable',
                Http::NOT_FOUND,
            );
        }

        return $this->successWithData(
            $updated,
            'Job updated successfully',
            Http::OK
        );
    }

    //  DELETE /employer/company/{uuid}/jobs/{job_id}

    public function destroy($uuid, $job_id): JsonResponse
    {
        $deleted = $this->service->deleteForCompany($uuid, $job_id);

        if (! $deleted) {

            return $this->error(
                'Job not found or cannot be deleted',
                Http::NOT_FOUND
            );
        }

        return $this->success('Job soft deleted successfully');
    }

    //   POST /employer/company/{uuid}/jobs/{job_id}/restore

    public function restore($uuid, $job_id): JsonResponse
    {
        $job = $this->service->restore($job_id);

        if (! $job) {

            return $this->error('Job not found or not restorable', Http::NOT_FOUND);
        }

        return $this->successWithData($job, 'Job restored');
    }

    public function publish($uuid, $job_id): JsonResponse
    {
        $job = $this->service->publish($uuid, $job_id, true);

        if ($job) {
            return $this->successWithData(
                $job,
                'Job published successfully',
                Http::OK,
            );
        }

        return $this->success(
            'Unable to publish job',
            Http::INTERNAL_SERVER_ERROR,
        );
    }

    public function unpublish($uuid, $job_id): JsonResponse
    {
        $job = $this->service->publish($uuid, $job_id, false);

        if ($job) {
            return $this->successWithData(
                $job,
                'Job unpublished successfully',
                Http::OK,
            );
        }

        return $this->success(
            'Unable to unpublish job',
            Http::INTERNAL_SERVER_ERROR,
        );
    }

    public function updateStatusToActive($company_id, $job_id)
    {
        // dd($company_id, $job_id);
        $response = $this->service->updateStatus($company_id, $job_id, true);

        if ($response->success) {
            return $this->successWithData(
                $response->job,
                'Job status updated successfully',
                Http::OK,
            );
        }

        return $this->success(
            'Unable to update status',
            Http::INTERNAL_SERVER_ERROR,
        );
    }

    public function updateStatusToInActive($uuid, $job_id)
    {
        $job = $this->service->updateStatus($uuid, $job_id, false);

        if ($job) {
            return $this->successWithData(
                new JobResource($job),
                'Job status updated successfully',
                Http::OK,
            );
        }

        return $this->success(
            'Unable to update status',
            Http::INTERNAL_SERVER_ERROR,
        );
    }

    public function applications(string $uuid, string $job_id)
    {
        // dd('reached here');
        $response = $this->service->getAllApplication($uuid, $job_id);
        // dd($response);

        if ($response->success) {
            // $applications = CompanyResource::collection($response->applications);
            return $this->successWithData(
                new JobListingResource($response->applications),
                $response->message,
                $response->status,
            );
        }

        return $this->error(
            $response->message,
            $response->status
        );
    }


    public function viewSingleApplication(string $uuid, string $job_id, string $applicationId)
    {
        $response = $this->service->getSingleApplication($uuid, $job_id, $applicationId);
        // dd($response);

        if ($response->success) {
            // $applications = CompanyResource::collection($response->applications);
            return $this->successWithData(
                new ApplicationResource($response->application),
                $response->message,
                $response->status,
            );
        }

        return $this->error(
            $response->message,
            $response->status
        );
    }


    public function updateApplicationStatus(string $uuid, string $job_id, string $applicationId, string $status)
    {
        $response = $this->service->updateApplicationStatus($uuid, $job_id, $applicationId, $status);

        if ($response->success) {
            return $this->successWithData(
                new ApplicationResource($response->application),
                $response->message,
                $response->status
            );
        }

        return $this->error(
            $response->message,
            $response->status
        );
    }
}