<?php

namespace App\Http\Resources\Employer;

use App\Http\Resources\Talent\ApplicationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobListingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'title'              => $this->title,
            'description'        => $this->description,
            'acceptance_criteria' => $this->acceptance_criteria,
            'salary'              => format_price((float) $this->price),

            // Status
            'status'             => $this->status,
            'is_published'       => $this->publication_status,

            // Location
            'state'              => $this->state,
            'country'            => $this->country,

            // Relations
            'skills'             => $this->whenLoaded('skills'),
            'job_levels'         => $this->whenLoaded('jobLevels'),
            'track'              => $this->whenLoaded('track'),
            'category'           => $this->whenLoaded('category'),
            'job_type'           => $this->whenLoaded('jobType'),
            'company'            => $this->whenLoaded('company'),
            'saved_jobs'        => $this->whenLoaded('bookmarks'),
            'works_mode'        => $this->whenLoaded('workModes'),

            // Extra computed data (custom)
            'total_applications' => $this->whenCounted('applications'),
            'applications' => ApplicationResource::collection(
                $this->whenLoaded('applications')
            ),
            // 'published_at'       => $this->published_at,
            'created_at'      => $this->created_at?->diffForHumans(),
            // 'application_link' => 
        ];
    }
}