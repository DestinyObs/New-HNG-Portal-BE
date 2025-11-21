<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Services\Interfaces\TagInterface;

class TagController extends Controller
{
    public function __construct(
        private readonly TagInterface $tagService
    ) {}

    /**
     * Display a listing of tags.
     */
    public function index()
    {
        $tags = $this->tagService->getAll();
        return $this->successWithData($tags, 'Tags retrieved successfully');
    }

    /**
     * Display the specified tag.
     */
    public function show(Tag $tag)
    {
        return $this->successWithData($tag, 'Tag retrieved successfully');
    }
}

