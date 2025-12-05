<?php

namespace App\Repositories\Interfaces;

use App\Models\Company;

interface CompanyRepositoryInterface
{
    public function create(array $data);

    public function show(string $uuid);

    public function update(array $data, string $uuid);

    public function updateLogo(mixed $file, string $uuid);

    public function getApplications(string $companyUuid, array $filters = [], int $perPage = 15);
}