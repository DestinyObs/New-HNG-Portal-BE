<?php

namespace App\Repositories\Interfaces;

interface TagRepositoryInterface
{
    public function fetchAll();
    public function findById(string $id);
    public function create(array $data);
    public function update(string $id, array $data);
    public function destroy(string $id);
}
