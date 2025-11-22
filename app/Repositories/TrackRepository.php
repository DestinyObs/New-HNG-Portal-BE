<?php
namespace App\Repositories;

use App\Models\Track;

class TrackRepository
{
    public function all()
    {
        return Track::all();
    }

    public function create(array $data)
    {
        return Track::create($data);
    }

    public function update(array $data, string $id)
    {
        $track = Track::findOrFail($id);
        $track->update($data);
        return $track;
    }

    public function delete(string $id)
    {
        return Track::destroy($id);
    }

    public function find(string $id): ?Track
    {
        return Track::find($id);
    }
}
