<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Auth\AuthenticationException;
use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register exception handling callbacks.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Custom logging or reporting logic can go here
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception): Response|JsonResponse
    {
        // Handle validation errors
        if ($exception instanceof ValidationException && $request->is('api/*')) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $exception->errors(),
            ], 422);
        }

        // Handle API requests
        if ($request->is('api/*')) {
            return $this->renderApiException($exception);
        }

        // Fallback to default Laravel web rendering
        return parent::render($request, $exception);
    }

    /**
     * Handle unauthenticated requests (API vs web).
     */
    protected function unauthenticated($request, AuthenticationException $exception): JsonResponse|Response
    {
        if ($request->is('api/*')) {
            return response()->json([
                'message' => 'Unauthenticated',
            ], 401);
        }

        return parent::unauthenticated($request, $exception);
    }

    /**
     * Render API exceptions as JSON responses.
     */
    protected function renderApiException(Throwable $exception): JsonResponse
    {
        // Method Not Allowed
        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'message' => 'Method not allowed',
            ], 405);
        }

        // Token mismatch (CSRF)
        if ($exception instanceof TokenMismatchException) {
            return response()->json([
                'message' => 'CSRF token mismatch',
            ], 419);
        }

        // Too many requests / throttling
        if ($exception instanceof ThrottleRequestsException) {
            return response()->json([
                'message' => 'Too many requests',
            ], 429);
        }

        // Other uncaught exceptions
        return response()->json([
            'message' => app()->isLocal() ? $exception->getMessage() : 'Oops! An error occurred',
        ], 500);
    }
}
