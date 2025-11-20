<?php

namespace App\Services\Interfaces;

interface LocationServiceInterface
{
    public function getAllLocations();
    public function createLocation(array $data);
    public function getLocationById(string $id);
    public function updateLocation(string $id, array $data);
    public function deleteLocation(string $id);
}
