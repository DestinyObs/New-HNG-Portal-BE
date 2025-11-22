<?php

namespace App\Services;

use App\Repositories\TagRepository;

class TagService
{
    protected $tagRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }


    // Add service methods for job type management here
    public function getAllTags()
    {
        // Logic to retrieve all job types
        return $this->tagRepository->fetchAll();
    }

    public function createTag(array $data)
    {
        // Logic to create a new job type
        return $this->tagRepository->create($data);

    }

    public function getTagById(string $id)
    {
        // Logic to retrieve a job type by ID
        return $this->tagRepository->findById($id);
    }


    public function updateTag(string $id, array $data)
    {
        // Logic to update a job type
        return $this->tagRepository->update($id, $data);
    }

    public function deleteTag(string $id)
    {
        // Logic to delete a job type
        return $this->tagRepository->destroy($id);
    }
}
