<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * URIs that should be excluded from CSRF verification.
     *
     * We exclude API routes here to avoid CSRF failures when API clients
     * (e.g. Swagger UI or other browser-based API explorers) send
     * requests with cookies. API routes should be stateless and rely on
     * token auth instead.
     *
     * Adjust this list if you want to selectively protect specific
     * endpoints.
     *
     * @var array<int,string>
     */
    protected $except = [
        'api/*',
    ];
}
