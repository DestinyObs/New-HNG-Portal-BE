<?php

namespace App\Http\Middleware;

use App\Enums\Http;
use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    use ApiResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        //? check if user does not exist and current role provied is not in array
        if (!$user && !in_array($user->role, $roles)) {
            return $this->error(
                "You are not authorized to access this resource",
                Http::FORBIDDEN,
            );
        }

        return $next($request);
    }
}
