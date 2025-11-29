<?php

namespace App\Http\Middleware;

use App\Enums\Http;
use App\Models\JobListing;
use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureJobBelongsToCompany
{
    use ApiResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $companyId = $request->route('companyId');
        $jobId   = $request->route('job_id');
        // dd($company, $jobId);

        $job = JobListing::where('id', $jobId)
            ->where('company_id', $companyId)
            ->first();

        if (!$job) {
            return $this->error(
                'This job does not belong to your company.',
                Http::FORBIDDEN
            );
        }

        // Attach job into the request
        $request->merge(['job' => $job]);

        return $next($request);
    }
}