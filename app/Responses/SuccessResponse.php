<?php

declare(strict_types=1);

namespace App\Responses;

use App\Enums\Http;
use Flugg\Responder\Http\Responses\SuccessResponseBuilder;

class SuccessResponse extends ApiResponse
{
     private function __construct(
          private mixed $data,
          private ?string $resourceKey = null,
          private array $meta = [],
     ) {}

     public static function make(mixed $data = null, Http $status = Http::OK, array $meta = [], ?string $resourceKey = null, array $headers = [])
     {
          return (new self($data, $resourceKey, $meta))
               ->withStatus($status)
               ->withHeaders($headers);
     }

     protected function responseBuilder(): SuccessResponseBuilder
     {
          return responder()->success(
               data: $this->data,
               resourceKey: $this->resourceKey
          )->meta($this->meta);
     }
}
