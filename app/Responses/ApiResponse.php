<?php

declare(strict_types=1);

namespace App\Responses;

use App\Enums\Http;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Responsable;
use Flugg\Responder\Http\Responses\ErrorResponseBuilder;
use Flugg\Responder\Http\Responses\SuccessResponseBuilder;

abstract class ApiResponse implements Responsable
{
     protected Http $statusCode = Http::OK;

     protected array $headers = [];

     abstract protected function responseBuilder(): ErrorResponseBuilder|SuccessResponseBuilder;

     public function toResponse($request): JsonResponse
     {
          return $this->responseBuilder()->respond(
               status: $this->statusCode->value,
               headers: $this->headers
          );
     }

     protected function withStatus(Http $status): self
     {
          $this->statusCode = $status;

          return $this;
     }

     protected function withHeaders(array $headers): self
     {
          $this->headers = $headers;

          return $this;
     }
}
