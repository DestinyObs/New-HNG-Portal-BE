<?php

namespace App\Http\Resources\Talent;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id'                 => $this->id,
            'title'              => $this->title,
            'description'        => $this->description,
            'acceptance_criteria' => $this->acceptance_criteria,
            'salary'              => (int) $this->price,

            // Status
            'status'             => $this->status,
            'is_published'       => $this->publication_status,

            // Bookmark / Saved Job
            'is_saved' => auth()->check()
                ? auth()->user()->bookmarks()->where('job_listing_id', $this->id)->exists()
                : false,

            // Location
            'state'              => $this->whenLoaded('states'),
            'country'            => $this->whenLoaded('countries'),

            // Relations
            'skills'             => $this->whenLoaded('skills'),
            'job_levels'         => $this->whenLoaded('jobLevels'),
            'track'              => $this->whenLoaded('track'),
            'category'           => $this->whenLoaded('category'),
            'job_type'           => $this->whenLoaded('jobType'),
            'company'            => $this->whenLoaded('company'),
            'saved_jobs'        => $this->whenLoaded('bookmarks'),

            // Extra computed data (custom)
            'total_applications' => $this->whenCounted('applications'),
            // 'published_at'       => $this->published_at,
            'created_at'      => $this->created_at?->diffForHumans(),
        ];
    }
}