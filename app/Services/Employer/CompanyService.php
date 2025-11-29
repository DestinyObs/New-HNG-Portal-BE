<?php

namespace App\Services\Employer;

use App\Enums\Http;
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

    public function getAllApplication(string $uuid)
    {
        try {
            $applications = $this->companyRepository->getApplications($uuid);
            // dd($applications);

            return (object) [
                'success' => true,
                'message' => 'Applications retrieved successfully',
                'applications' => $applications,
                'status' => Http::OK,
            ];
        } catch (\Exception $e) {
            return (object) [
                'success' => false,
                'message' => 'Unable to retrieve applications',
                'status' => Http::INTERNAL_SERVER_ERROR,
            ];
        }
    }
}