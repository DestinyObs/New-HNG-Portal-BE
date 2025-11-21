<?php

namespace App\Repositories;

use App\Models\Skill;
use App\Repositories\Interfaces\SkillRepositoryInterface;

class SkillRepository implements SkillRepositoryInterface
{
    protected Skill $skill;
    /**
     * Create a new class instance.
     */
    public function __construct(Skill $skill)
    {
        $this->skill = $skill;
    }

    // Add repository methods for job type data access here
    public function fetchAll()
    {
        // Logic to fetch all job types from the data source
        return $this->skill::all();
    }

    public function findById(string $id)
    {
        // Logic to find a job type by ID from the data source
        return $this->skill::findOrFail($id);
    }


    // create
    public function create(array $data){
        return $this->skill->create($data);
    }    

    // update
    public function update(string $id, array $data){
        $skill = $this->skill::findOrFail($id);
        $skill->update($data);
        return $skill;
    }

    // delete
    public function destroy(string $id){
        $skill = $this->skill::findOrFail($id);
        return $skill->delete();
    }
}
