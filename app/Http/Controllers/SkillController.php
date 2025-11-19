<?php

namespace App\Http\Controllers;

use App\Enums\Http;
use App\Http\Requests\SkillRequest;
use App\Services\SkillService;
use Illuminate\Http\Request;

class SkillController extends Controller
{
     protected $skillService;

    public function __construct(SkillService $skillService)
    {
        $this->skillService = $skillService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->skillService->getAllSkills();
        return $this->successWithData($data, 'Job types retrieved successfully'); // 200
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SkillRequest $request) {
        $validated = $request->validated();
        $data = $this->skillService->createSkill($validated);
        return $this->successWithData($data, 'created', Http::CREATED); // 201
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = $this->skillService->getSkillById($id);
        return $this->successWithData($data, 'Job type retrieved successfully'); // 200
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SkillRequest $request, string $id)
    {
        $validated = $request->validated();
        $data = $this->skillService->updateSkill($id, $validated);
        return $this->successWithData($data, 'updated'); // 200
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->skillService->deleteSkill($id);
        return $this->success('deleted', Http::NO_CONTENT); // 204
    }
}
