<?php

namespace App\Http\Middleware;

use App\Enums\Http;
use App\Models\Company;
use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanyOwner
{
    use ApiResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $companyId = $request->route('companyId');
        $user = $request->user();

        $company = Company::where('id', $companyId)
            ->where('user_id', $user->id)
            ->first();

        if (!$company) {

            return $this->error(
                'This company does not belong to you.',
                Http::FORBIDDEN
            );
        }

        // Attach company to request so you don’t requery in controllers
        // $request->merge(['company' => $company]);

        return $next($request);
    }
}
