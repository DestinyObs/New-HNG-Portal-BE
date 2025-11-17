<?php

declare(strict_types=1);

namespace App\Responses;

use App\Enums\Http;
use Flugg\Responder\Http\Responses\ErrorResponseBuilder;

class ErrorResponse extends ApiResponse
{
     private function __construct(
          private string $message,
          private ?string $errorCode = null
     ) {}

     public static function make(string $message, Http $status = Http::BAD_REQUEST, ?string $errorCode = null, array $headers = [])
     {
          return (new self($message, $errorCode))
               ->withStatus($status)
               ->withHeaders($headers);
     }

     protected function responseBuilder(): ErrorResponseBuilder
     {
          return responder()->error($this->errorCode, $this->message);
     }
}
