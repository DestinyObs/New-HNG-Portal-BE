<?php

namespace App\Services;

use App\Repositories\LocationRepository;

class LocationService
{
    protected $locationRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(LocationRepository $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }


    // Add service methods for job type management here
    public function getAllLocations()
    {
        // Logic to retrieve all job types
        return $this->locationRepository->fetchAll();
    }

    public function createLocation(array $data)
    {
        // Logic to create a new job type
        return $this->locationRepository->create($data);

    }

    public function getLocationById(string $id)
    {
        // Logic to retrieve a job type by ID
        return $this->locationRepository->findById($id);
    }


    public function updateLocation(string $id, array $data)
    {
        // Logic to update a job type
        return $this->locationRepository->update($id, $data);
    }

    public function deleteLocation(string $id)
    {
        // Logic to delete a job type
        return $this->locationRepository->destroy($id);
    }
}
