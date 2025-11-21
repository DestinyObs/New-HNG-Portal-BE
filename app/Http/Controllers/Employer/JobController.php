<?php

namespace App\Http\Controllers\Employer;

use App\Enums\Http;
use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Employer\StoreDraftJobRequest;
use App\Http\Requests\Employer\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Services\JobService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\JobListing;
use Illuminate\Validation\Rule;

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
        $result = $this->service->listForCompany($uuid, $request->only(['title', 'job_type_id', 'category_id']), $perPage);

        return response()->json($result);
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
            'active',
            true
        );

        //? case when job is added successfully
        if ($response->success) {
            return $this->successWithData(
                $response->data,
                $response->message,
                $response->status
            );
        }

        //? handle error messages
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
            Status::DRAFT,
        );

        //? case when job is added successfully
        if ($response->success) {
            return $this->successWithData(
                $response->data,
                $response->message,
                $response->status
            );
        }

        //? handle error messages
        return $this->error(
            $response->message,
            $response->status
        );
    }


    //   GET /employer/company/{uuid}/jobs/{job_id}

    public function show($uuid, $job_id): JsonResponse
    {
        $job = $this->service->getForCompany($uuid, $job_id);

        if (!$job) {
            return $this->error("Job not found", Http::NOT_FOUND);
        }

        return $this->success("Job retrieved successfully", $job);
    }


    //   PUT /employer/company/{uuid}/jobs/{job_id}

    public function update(UpdateJobRequest $request, $uuid, $job_id): JsonResponse
    {
        dd($request->validated());
        // $validated = $request->validate([
        //     'title' => 'sometimes|required|string|max:255',
        //     'description' => 'sometimes|required|string',
        //     'acceptance_criteria' => 'nullable|string',
        //     'candidate_location_id' => 'nullable|uuid|exists:locations,id',
        //     'price' => 'nullable|numeric',
        //     'track_id' => 'nullable|uuid|exists:tracks,id',
        //     'category_id' => 'nullable|uuid|exists:categories,id',
        //     'job_type_id' => 'nullable|uuid|exists:job_types,id',
        // ]);

        $updated = $this->service->updateForCompany($uuid, $job_id, $request->validated());

        if (!$updated) {
            return $this->error(
                'Job not found or not editable',
                Http::NOT_FOUND,
            );
        }

        return $this->successWithData(
            "Job updated",
            $updated,
            Http::OK
        );
    }


    //  DELETE /employer/company/{uuid}/jobs/{job_id}

    public function destroy($uuid, $job_id): JsonResponse
    {
        $deleted = $this->service->deleteForCompany($uuid, $job_id);

        if (!$deleted) {
            return response()->json(['message' => 'Job not found or cannot be deleted'], 404);
        }

        return response()->json(['message' => 'Job soft-deleted']);
    }


    //   POST /employer/company/{uuid}/jobs/{job_id}/restore

    public function restore($uuid, $job_id): JsonResponse
    {
        $job = $this->service->restore($job_id);

        if (!$job) {
            return response()->json(['message' => 'Job not found or not restorable'], 404);
        }

        return response()->json(['message' => 'Job restored', 'data' => $job]);
    }


    public function publish($uuid, $job_id): JsonResponse
    {
        $job = $this->service->publish($uuid, $job_id, true);

        if ($job) {
            return $this->success(
                "Job published successfully",
                Http::OK,
            );
        }

        return $this->success(
            "Unable to publish job",
            Http::INTERNAL_SERVER_ERROR,
        );
    }

    public function unpublish($uuid, $job_id): JsonResponse
    {
        $job = $this->service->publish($uuid, $job_id, false);

        if ($job) {
            return $this->success(
                "Job unpublished successfully",
                Http::OK,
            );
        }

        return $this->success(
            "Unable to unpublish job",
            Http::INTERNAL_SERVER_ERROR,
        );
    }
}