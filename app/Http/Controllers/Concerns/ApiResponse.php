<?php

declare(strict_types=1);

namespace App\Http\Controllers\Concerns;

use App\Enums\Http;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponse
{
    /**
     * Return a success response.
     */
    protected function success(
        string $message = 'Operation successful',
        Http $status = Http::OK,
        array $headers = []
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'status' => $status->value,
        ], $status->value, $headers);
    }

    /**
     * Return an error response.
     */
    protected function error(
        string $message,
        Http $status = Http::BAD_REQUEST,
        array $headers = []
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'status' => $status->value,
        ], $status->value, $headers);
    }

    /**
     * Return a success response with data.
     */
    protected function successWithData(
        mixed $data,
        string $message = 'Operation successful',
        Http $status = Http::OK,
        array $headers = []
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'status' => $status->value,
        ], $status->value, $headers);
    }

    /**
     * Return a paginated response.
     */
    protected function paginated(
        LengthAwarePaginatorContract|AnonymousResourceCollection $paginator,
        string $message = 'Data retrieved successfully',
        Http $status = Http::OK,
        array $headers = []
    ): JsonResponse {
        // Handle Laravel Resource Collections
        if ($paginator instanceof AnonymousResourceCollection) {
            $paginator = $paginator->resource;
        }

        // Ensure we have a LengthAwarePaginator
        if (! $paginator instanceof LengthAwarePaginator) {
            throw new \InvalidArgumentException('Paginator must be an instance of LengthAwarePaginator');
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $paginator->items(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
                'has_more_pages' => $paginator->hasMorePages(),
            ],
            'status' => $status->value,
        ], $status->value, $headers);
    }

    /**
     * Return a created response.
     */
    protected function created(
        string $message = 'Resource created successfully',
        mixed $data = null,
        array $headers = []
    ): JsonResponse {
        $response = [
            'success' => true,
            'message' => $message,
            'status' => Http::CREATED->value,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, Http::CREATED->value, $headers);
    }

    /**
     * Return a no content response.
     */
    protected function noContent(
        array $headers = []
    ): JsonResponse {
        return response()->json(null, Http::NO_CONTENT->value, $headers);
    }

    /**
     * Return a not found response.
     */
    protected function notFound(
        string $message = 'Resource not found',
        array $headers = []
    ): JsonResponse {
        return $this->error($message, Http::NOT_FOUND, $headers);
    }

    /**
     * Return an unauthorized response.
     */
    protected function unauthorized(
        string $message = 'Unauthorized',
        array $headers = []
    ): JsonResponse {
        return $this->error($message, Http::UNAUTHORIZED, $headers);
    }

    /**
     * Return a forbidden response.
     */
    protected function forbidden(
        string $message = 'Forbidden',
        array $headers = []
    ): JsonResponse {
        return $this->error($message, Http::FORBIDDEN, $headers);
    }

    /**
     * Return an unprocessable entity response.
     */
    protected function unprocessable(
        string $message = 'Validation failed',
        array $errors = [],
        array $headers = []
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $message,
            'status' => Http::UNPROCESSABLE_ENTITY->value,
        ];

        if (! empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, Http::UNPROCESSABLE_ENTITY->value, $headers);
    }
}
