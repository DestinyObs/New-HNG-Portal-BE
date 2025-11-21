<?php

namespace App\Http\Controllers;

use App\Models\Track;
use App\Services\Interfaces\TrackInterface;

class TrackController extends Controller
{
    public function __construct(
        private readonly TrackInterface $trackService
    ) {}

    /**
     * Display a listing of tracks.
     */
    public function index()
    {
        $tracks = $this->trackService->getAll();
        return $this->successWithData($tracks, 'Tracks retrieved successfully');
    }

    /**
     * Display the specified track.
     */
    public function show(Track $track)
    {
        return $this->successWithData($track, 'Track retrieved successfully');
    }
}

