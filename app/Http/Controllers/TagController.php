<?php

namespace App\Http\Controllers;

use App\Enums\Http;
use App\Http\Requests\TagRequest;
use App\Services\TagService;
use Illuminate\Http\Request;

class TagController extends Controller
{
    protected $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }
        
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->tagService->getAllTags();
        if ($data->isEmpty()) {
            return $this->error('Tag not found!.');
        }
        return $this->successWithData($data, 'Tags retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TagRequest $request)
    {
        $validated = $request->validated();
        $data = $this->tagService->createTag($validated);
        return $this->successWithData($data, 'created', Http::CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->tagService->getTagById($id);
        return $this->successWithData($data, 'Tag retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TagRequest $request, string $id)
    {
        $validated = $request->validated();
        $data = $this->tagService->updateTag($id, $validated);
        return $this->successWithData($data, 'updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->tagService->deleteTag($id);
        return $this->success('deleted', Http::NO_CONTENT);
    }
}
