<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Http;
use App\Http\Controllers\Controller;
use App\Http\Requests\LocationRequest;
use App\Services\LocationService;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    protected $locationService;

    public function __construct(LocationService $locationService){
        $this->locationService = $locationService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->locationService->getAllLocations();
        if($data->isEmpty()){
            return $this->error('Location not found!.');
        }
        return $this->successWithData($data, 'location retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LocationRequest $request)
    {
        $validated = $request->validated();
        $data = $this->locationService->createLocation($validated);
        return $this->successWithData($data, 'created', Http::CREATED);        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->locationService->getLocationById($id);
        return $this->successWithData($data, 'Location retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LocationRequest $request, string $id)
    {
        $validated = $request->validated();
        $data = $this->locationService->updateLocation($id, $validated);
        return $this->successWithData($data, 'updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->locationService->deleteLocation($id);
        return $this->success('deleted', Http::NO_CONTENT);
    }
}
