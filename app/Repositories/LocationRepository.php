<?php

namespace App\Repositories;

use App\Models\Location;
use App\Repositories\Interfaces\LocationRepositoryInterface;

class LocationRepository implements LocationRepositoryInterface
{

    protected Location $location;
    /**
     * Create a new class instance.
     */
    public function __construct(Location $location)
    {
        $this->location = $location;
    }

    // Add repository methods for job type data access here
    public function fetchAll()
    {
        // Logic to fetch all job types from the data source
        return $this->location::all();
    }

    public function findById(string $id)
    {
        // Logic to find a job type by ID from the data source
        return $this->location::findOrFail($id);
    }


    // create
    public function create(array $data){
        return $this->location->create($data);
    }    

    // update
    public function update(string $id, array $data){
        $location = $this->location::findOrFail($id);
        $location->update($data);
        return $location;
    }

    // delete
    public function destroy(string $id){
        $location = $this->location::findOrFail($id);
        return $location->delete();
    }
}
