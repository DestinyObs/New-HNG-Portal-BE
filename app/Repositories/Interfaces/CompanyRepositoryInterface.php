<?php

namespace App\Repositories\Interfaces;

interface CompanyRepositoryInterface
{
    public function create(array $data);

    public function show(string $uuid);

    public function update(array $data, string $uuid);

    public function updateLogo(mixed $file, string $uuid);
}