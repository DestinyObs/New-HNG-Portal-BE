<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employer\StoreCompanyRequest;
use App\Http\Requests\Employer\UpdateCompanyLogoRequest;
use App\Http\Requests\Employer\UpdateCompanyRequest;
use App\Services\Employer\CompanyService;

class CompanyController extends Controller
{
    public function __construct(private CompanyService $companyService) {}

    public function store(StoreCompanyRequest $request)
    {
        $company = $this->companyService->createCompany($request->validated());

        return $this->created('Company created successfully', $company);
    }

    public function show(string $uuid)
    {
        $company = $this->companyService->getCompany($uuid);

        return $this->successWithData($company, 'Company retrieved successfully');
    }

    public function update(UpdateCompanyRequest $request, string $uuid)
    {
        $company = $this->companyService->updateCompany($request->validated(), $uuid);

        return $this->successWithData($company, 'Company updated successfully');
    }

    public function updateLogo(UpdateCompanyLogoRequest $request, string $uuid)
    {
        $company = $this->companyService->updateCompanyLogo($request->file('logo'), $uuid);

        return $this->successWithData($company, 'Company Logo updated successfully');
    }
}
