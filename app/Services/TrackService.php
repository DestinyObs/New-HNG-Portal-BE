<?php
namespace App\Services;

use App\Repositories\TrackRepository;

class TrackService
{
    public function __construct(private TrackRepository $repo) {}

    public function listTracks()
    {
        return $this->repo->all();
    }

    public function createTrack(array $data)
    {
        return $this->repo->create($data);
    }

    public function updateTrack(array $data, string $id)
    {
        return $this->repo->update($data, $id);
    }

    public function deleteTrack(string $id)
    {
        $this->repo->delete($id);
    }
}
