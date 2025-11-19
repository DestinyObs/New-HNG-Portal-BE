<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
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
        $result = $this->service->listForCompany($uuid, $request->only(['title','job_type_id','category_id']), $perPage);

        return response()->json($result);
    }

    /**
     * POST /employer/company/{uuid}/jobs
     */
    public function store(Request $request, $uuid): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'acceptance_criteria' => 'nullable|string',
            'candidate_location_id' => 'nullable|uuid|exists:locations,id',
            'price' => 'nullable|numeric',
            'track_id' => 'nullable|uuid|exists:tracks,id',
            'category_id' => 'nullable|uuid|exists:categories,id',
            'job_type_id' => 'nullable|uuid|exists:job_types,id',
        ]);

        $job = $this->service->create($uuid, $validated);

        return response()->json([
            'message' => 'Job created',
            'data' => $job
        ], 201);
    }

    
    //   GET /employer/company/{uuid}/jobs/{job_id}
     
    public function show($uuid, $job_id): JsonResponse
    {
        $job = $this->service->getForCompany($uuid, $job_id);

        if (!$job) {
            return response()->json(['message' => 'Job not found'], 404);
        }

        return response()->json(['data' => $job]);
    }

    
    //   PUT /employer/company/{uuid}/jobs/{job_id}
     
    public function update(Request $request, $uuid, $job_id): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'acceptance_criteria' => 'nullable|string',
            'candidate_location_id' => 'nullable|uuid|exists:locations,id',
            'price' => 'nullable|numeric',
            'track_id' => 'nullable|uuid|exists:tracks,id',
            'category_id' => 'nullable|uuid|exists:categories,id',
            'job_type_id' => 'nullable|uuid|exists:job_types,id',
        ]);

        $updated = $this->service->updateForCompany($uuid, $job_id, $validated);

        if (!$updated) {
            return response()->json(['message' => 'Job not found or not editable'], 404);
        }

        return response()->json(['message' => 'Job updated', 'data' => $updated]);
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

    
    //   PUT /employer/company/{uuid}/jobs/{job_id}/publish
    //  Currently a placeholder because job_listings table has no status column.
     
    public function publish($uuid, $job_id): JsonResponse
    {
        return response()->json([
            "message" => "Publish/unpublish functionality is not yet available. Database support for job status is pending."
        ], 501);
    }

    
    //   PUT /employer/company/{uuid}/jobs/{job_id}/unpublish
    //  Currently a placeholder because job_listings table has no status column.
     
    public function unpublish($uuid, $job_id): JsonResponse
    {
        return response()->json([
            "message" => "Publish/unpublish functionality is not yet available. Database support for job status is pending."
        ], 501);
    }
}
