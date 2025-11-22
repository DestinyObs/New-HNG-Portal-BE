<?php

namespace App\Repositories;

use App\Models\Tag;

class TagRepository
{
    protected Tag $tag;
    /**
     * Create a new class instance.
     */
    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }

    // Add repository methods for job type data access here
    public function fetchAll()
    {
        // Logic to fetch all job types from the data source
        return $this->tag::all();
    }

    public function findById(string $id)
    {
        // Logic to find a job type by ID from the data source
        return $this->tag::findOrFail($id);
    }


    // create
    public function create(array $data){
        return $this->tag->create($data);
    }    

    // update
    public function update(string $id, array $data){
        $tag = $this->tag::findOrFail($id);
        $tag->update($data);
        return $tag;
    }

    // delete
    public function destroy(string $id){
        $tag = $this->tag::findOrFail($id);
        return $tag->delete();
    }

}
