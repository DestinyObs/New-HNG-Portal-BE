<?php

namespace App\Exceptions;

use App\Enums\Http;
use App\Http\Controllers\Concerns\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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
        if ($request->is('api/*')) {

            // Validation errors
            if ($exception instanceof ValidationException) {
                return $this->unprocessable(
                    'Validation failed, please check your input',
                    $exception->errors()
                );
            }

            // Authentication
            if ($exception instanceof AuthenticationException) {
                return $this->unauthorized('You are Unauthenticated, please log in');
            }

            // Authorization
            if ($exception instanceof AuthorizationException) {
                return $this->forbidden('You do not have permission to perform this action');
            }

            // Model not found
            if ($exception instanceof ModelNotFoundException) {
                $model = class_basename($exception->getModel());

                return $this->notFound("{$model} not found");
            }

            // Route not found
            if ($exception instanceof NotFoundHttpException) {
                return $this->notFound('Route not found, please check the URL');
            }

            // Method not allowed
            if ($exception instanceof MethodNotAllowedHttpException) {
                return $this->error('Method not allowed, please confirm the request method', Http::METHOD_NOT_ALLOWED);
            }

            // CSRF token mismatch
            if ($exception instanceof TokenMismatchException) {
                return $this->error('CSRF token mismatch, please confirm the origin of the request is registered', Http::UNAUTHORIZED);
            }

            // Too many requests / throttling
            if ($exception instanceof ThrottleRequestsException) {
                return $this->error('Too many requests, please try again later', Http::TOO_MANY_REQUESTS);
            }

            // 9️Database query errors
            if ($exception instanceof QueryException) {
                return $this->error(
                    app()->isLocal() ? $exception->getMessage() : 'Database  error occurred, please check your request',
                    Http::INTERNAL_SERVER_ERROR
                );
            }

            // Fallback for all other exceptions
            return $this->error(
                app()->isLocal() ? $exception->getMessage() : 'Oops! An error occurred, please refresh the page or try again later',
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
            return $this->unauthorized('You are Unauthenticated, please log in');
        }

        return parent::unauthenticated($request, $exception);
    }
}
