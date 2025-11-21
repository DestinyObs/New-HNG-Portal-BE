<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Services\Interfaces\LocationInterface;

class LocationController extends Controller
{
    public function __construct(
        private readonly LocationInterface $locationService
    ) {}

    /**
     * Display a listing of locations.
     */
    public function index()
    {
        $locations = $this->locationService->getAll();
        return $this->successWithData($locations, 'Locations retrieved successfully');
    }

    /**
     * Display the specified location.
     */
    public function show(Location $location)
    {
        return $this->successWithData($location, 'Location retrieved successfully');
    }
}

