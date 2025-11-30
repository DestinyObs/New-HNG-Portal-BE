<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use App\Traits\ApiResponse;

class DashboardController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return $this->unauthorized('User not authenticated.');
        }

        $company = Company::where('user_id', $user->id)->first();

        if (!$company) {
            return $this->notFound('Company not found for this employer.');
        }

        // COUNT ACTIVE JOBS
        $activeJobs = $company->jobs()->where('status', 'active')->count();

        // COUNT TOTAL APPLICANTS
        $totalApplicants = $company->applications()->count();

        return $this->successWithData([
            'active_jobs'      => $activeJobs,
            'total_applicants' => $totalApplicants
        ], 'Dashboard analysis retrieved successfully.');
    }
}
