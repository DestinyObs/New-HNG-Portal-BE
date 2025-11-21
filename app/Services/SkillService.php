<?php

namespace App\Services;

use App\Repositories\SkillRepository;
use App\Services\Interfaces\SkillServiceInterface;
use Illuminate\Support\Str;

class SkillService implements SkillServiceInterface
{
    protected $skillRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(SkillRepository $skillRepository)
    {
        $this->skillRepository = $skillRepository;
    }


    // Add service methods for job type management here
    public function getAllSkills()
    {
        // Logic to retrieve all job types
        return $this->skillRepository->fetchAll();
    }

    public function createSkill(array $data)
    {
        // Logic to create a new job type
        $data['slug'] = Str::slug($data['name']);
        return $this->skillRepository->create($data);

    }

    public function getSkillById(string $id)
    {
        // Logic to retrieve a job type by ID
        return $this->skillRepository->findById($id);
    }


    public function updateSkill(string $id, array $data)
    {
        // Logic to update a job type
        $data['slug'] = Str::slug($data['name']);
        return $this->skillRepository->update($id, $data);
    }

    public function deleteSkill(string $id)
    {
        // Logic to delete a job type
        return $this->skillRepository->destroy($id);
    }
}
