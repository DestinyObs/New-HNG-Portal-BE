<?php

namespace App\Http\Resources\Talent;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class JobCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
        return [
            'data' => JobResource::collection($this->collection),

            // Add your custom meta here
            // 'meta' => [
            //     'total'     => $this->collection->count(),
            //     'timestamp' => now()->toDateTimeString(),
            //     'generated' => 'Job listing API v1',
            // ]
        ];
    }
}
