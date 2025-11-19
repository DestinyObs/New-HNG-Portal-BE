<?php

namespace App\Http\Controllers\Employer;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Employer\CompanyService;
use App\Repositories\Employer\CompanyRepository;
use App\Traits\ApiResponse;


class CompanyController extends Controller
{
     use ApiResponse;

    public function __construct(
        private CompanyRepository $companyRepository,
        private CompanyService $companyService
    ) {}

    // ===== APPLICATION MANAGEMENT ======
    public function getJobApplicants(Request $request, string $companyUuid, string $jobUuid)
    {
        try {
            // Verify ownership
            $this->companyService->verifyCompanyJobOwnership($companyUuid, $jobUuid);

            // Get applicants with filters
            $filters = $request->only(['status', 'search', 'per_page']);
            $applicants = $this->companyService->getJobApplicants($companyUuid, $jobUuid, $filters);

            return $this->paginated($applicants, 'Applicants retrieved successfully');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFound('Company or job not found');
        } catch (Exception $e) {
            return $this->error('Failed to retrieve applicants: ' . $e->getMessage());
        }
    }
}
