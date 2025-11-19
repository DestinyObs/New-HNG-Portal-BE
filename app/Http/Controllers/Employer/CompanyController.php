<?php

namespace App\Http\Controllers\Employer;

use Exception;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Employer\CompanyService;
use App\Http\Requests\SearchTalentsRequest;
use App\Repositories\Employer\CompanyRepository;

class CompanyController extends Controller
{
    use ApiResponse;

    public function __construct(
        private CompanyRepository $companyRepository,
        private CompanyService $companyService
    ) {}

    // APPLICATION MANAGEMENT
    public function getJobApplicants(Request $request, string $companyUuid, string $jobUuid)
    {
        try {

            $this->companyService->verifyCompanyJobOwnership($companyUuid, $jobUuid);


            $filters = $request->only(['status', 'search', 'per_page']);
            $applicants = $this->companyService->getJobApplicants($companyUuid, $jobUuid, $filters);

            return $this->paginated($applicants, 'Applicants retrieved successfully');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFound('Company or job not found');
        } catch (Exception $e) {
            return $this->error('Failed to retrieve applicants: ' . $e->getMessage());
        }
    }

    // TALENTS SEARCH
    public function searchTalents(SearchTalentsRequest $request, string $companyUuid)
    {
        try {
            $company = $this->companyRepository->findCompanyById($companyUuid);

            $filters = $request->validated();

            $talents = $this->companyService->searchTalents($company->id, $filters);

            return $this->paginated($talents, 'Talents retrieved successfully');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFound('Company not found');
        } catch (Exception $e) {
            return $this->error('Failed to search talents: ' . $e->getMessage());
        }
    }
}
