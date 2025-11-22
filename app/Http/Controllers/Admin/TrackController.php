<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Http;
use App\Http\Controllers\Concerns\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTrackRequest;
use App\Http\Requests\Admin\UpdateTrackRequest;
use App\Services\TrackService;

class TrackController extends Controller
{
    use ApiResponse;

    public function __construct(private TrackService $service) {}

    /**
     * List all tracks
     */
    public function index()
    {
        $tracks = $this->service->listTracks();

        return $this->successWithData(
            $tracks,
            'Tracks retrieved successfully',
            Http::OK
        );
    }

    /**
     * Create a new track
     */
    public function store(StoreTrackRequest $request)
    {
        $track = $this->service->createTrack($request->validated());

        return $this->created(
            'Track created successfully',
            $track
        );
    }

    /**
     * Update an existing track
     */
    public function update(UpdateTrackRequest $request, string $id)
    {
        $track = $this->service->updateTrack($request->validated(), $id);

        return $this->successWithData(
            $track,
            'Track updated successfully',
            Http::OK
        );
    }

    /**
     * Delete a track
     */

     public function destroy(string $id)
    {
        $this->service->deleteTrack($id);

        return $this->success(
            "Track deleted successfully"
        );
    }
}
