<?php

namespace App\Exceptions;

use App\Http\Controllers\Concerns\ApiResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Enums\Http;

class Handler extends ExceptionHandler
{
    use ApiResponse; 

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Optional: custom logging
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception): Response|JsonResponse
    {
        // 🚨 1. Whitelist Swagger UI (DON'T force JSON here)
        if (
            $request->is('api/documentation') ||
            $request->is('docs') ||
            $request->is('swagger*')
        ) {
            return parent::render($request, $exception);
        }
        
        
        if ($request->is('api/*')) {

            // Validation errors
            if ($exception instanceof ValidationException) {
                return $this->unprocessable(
                    'Validation failed',
                    $exception->errors()
                );
            }

            // Authentication
            if ($exception instanceof AuthenticationException) {
                return $this->unauthorized('Unauthenticated');
            }

            // Authorization
            if ($exception instanceof AuthorizationException) {
                return $this->forbidden('You do not have permission');
            }

            // Model not found
            if ($exception instanceof ModelNotFoundException) {
                $model = class_basename($exception->getModel());
                return $this->notFound("{$model} not found");
            }

            // Route not found
            if ($exception instanceof NotFoundHttpException) {
                return $this->notFound('Route not found');
            }

            // Method not allowed
            if ($exception instanceof MethodNotAllowedHttpException) {
                return $this->error('Method not allowed', Http::METHOD_NOT_ALLOWED);
            }

            // CSRF token mismatch
            if ($exception instanceof TokenMismatchException) {
                return $this->error('CSRF token mismatch', Http::UNAUTHORIZED);
            }

            // Too many requests / throttling
            if ($exception instanceof ThrottleRequestsException) {
                return $this->error('Too many requests', Http::TOO_MANY_REQUESTS);
            }

            // 9️Database query errors
            if ($exception instanceof QueryException) {
                return $this->error(
                    app()->isLocal() ? $exception->getMessage() : 'Database error',
                    Http::INTERNAL_SERVER_ERROR
                );
            }

            // Fallback for all other exceptions
            return $this->error(
                app()->isLocal() ? $exception->getMessage() : 'Oops! An error occurred',
                Http::INTERNAL_SERVER_ERROR
            );
        }

        // Fallback to default web rendering
        return parent::render($request, $exception);
    }

    /**
     * Handle unauthenticated requests.
     */
    protected function unauthenticated($request, AuthenticationException $exception): JsonResponse|Response
    {
        if ($request->is('api/*')) {
            return $this->unauthorized('Unauthenticated');
        }

        return parent::unauthenticated($request, $exception);
    }
}
