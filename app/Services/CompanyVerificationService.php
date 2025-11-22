<?php

namespace App\Services;

use App\Repositories\CompanyRepository;
use Exception;

class CompanyVerificationService
{
    public function __construct(private CompanyRepository $repo) {}

    public function getPendingCompanies()
    {
        return $this->repo->getPending();
    }

    public function verifyCompany(string $uuid, array $data)
    {
        $company = $this->repo->find($uuid);

        if (!$company) {
            throw new Exception('Company not found', 404);
        }

        return $this->repo->updateVerification($uuid, $data);
    }
}
