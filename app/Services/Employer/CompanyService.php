<?php

namespace App\Services\Employer;

use App\Repositories\Employer\CompanyRepository;

class CompanyService
{
    public function __construct(private CompanyRepository $companyRepository) {}

    public function createCompany(array $data)
    {
        return $this->companyRepository->create($data);
    }

    public function getCompany(string $uuid)
    {
        return $this->companyRepository->show($uuid);
    }

    public function updateCompany(array $data, string $uuid)
    {
        return $this->companyRepository->update($data, $uuid);
    }

    public function updateCompanyLogo($file, $uuid)
    {
        return $this->companyRepository->updateLogo($file, $uuid);
    }
}
