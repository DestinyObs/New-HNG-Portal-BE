<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Http;
use App\Http\Controllers\Controller;
use App\Http\Requests\JobTypeRequest;
use App\Models\JobType;
use App\Services\JobTypeService;
use Illuminate\Http\Request;

class JobTypeController extends Controller
{
    protected $jobTypeService;

    public function __construct(JobTypeService $jobTypeService)
    {
        $this->jobTypeService = $jobTypeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->jobTypeService->getAllJobTypes();
        return $this->successWithData($data, 'Job types retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JobTypeRequest $request) {
        $validated = $request->validated();
        $data = $this->jobTypeService->createJobType($validated);
        return $this->successWithData($data, 'created', Http::CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = $this->jobTypeService->getJobTypeById($id);
        return $this->successWithData($data, 'Job type retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JobTypeRequest $request, string $id)
    {
        $validated = $request->validated();
        $data = $this->jobTypeService->updateJobType($id, $validated);
        return $this->successWithData($data, 'updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->jobTypeService->deleteJobType($id);
        return $this->success('deleted', Http::NO_CONTENT);
    }
}
