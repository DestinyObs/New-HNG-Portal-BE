<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Http;
use App\Http\Controllers\Concerns\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\CompanyVerificationService;
use App\Http\Requests\Admin\VerifyCompanyRequest;

class CompanyVerificationController extends Controller
{
    use ApiResponse;

    public function __construct(private CompanyVerificationService $service) {}

    /**
     * Get companies pending verification
     */
    public function pending()
    {
        $companies = $this->service->getPendingCompanies();

        return $this->successWithData(
            $companies,
            'Pending companies retrieved successfully'
        );
    }

    /**
     * Approve or reject a company verification
     */
    public function verify(VerifyCompanyRequest $request, string $uuid)
    {
        $company = $this->service->verifyCompany($uuid, $request->validated());

        return $this->successWithData(
            $company,
            'Company verification updated successfully',
            Http::OK
        );
    }
}
