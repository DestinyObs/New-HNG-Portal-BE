<?php

namespace App\Http\Resources\Talent;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'job_id' => $this->job_id,
            'cover_letter' => $this->cover_letter,
            'status' => $this->status,
            'portfolio_link' => $this->portfolio_link,
            'resume' => $this->getFirstMediaUrl('resumes') ?: null,
            'user' => $this->whenLoaded('user'),
            'job' => $this->whenLoaded('job'),
            'company' => $this->whenLoaded('job.company'),
            'date_added' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}