<?php

namespace App\Repositories;

use App\Models\JobType;
use PHPUnit\Util\PHP\Job;

class JobTypeRepository
{

    protected JobType $jobType;
    /**
     * Create a new class instance.
     */
    public function __construct(JobType $jobType)
    {
        $this->jobType = $jobType;
    }

    // Add repository methods for job type data access here
    public function fetchAll()
    {
        // Logic to fetch all job types from the data source
        return $this->jobType::all();
    }

    public function findById(string $id)
    {
        // Logic to find a job type by ID from the data source
        return $this->jobType::findOrFail($id);
    }


    // create
    public function create(array $data){
        return $this->jobType->create($data);
    }    

    // update
    public function update(string $id, array $data){
        $jobType = $this->jobType::findOrFail($id);
        $jobType->update($data);
        return $jobType;
    }

    // delete
    public function destroy(string $id){
        $jobType = $this->jobType::findOrFail($id);
        return $jobType->delete();
    }


}
