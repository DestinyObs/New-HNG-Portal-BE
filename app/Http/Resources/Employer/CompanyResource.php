<?php

namespace App\Http\Resources\Employer;

use App\Http\Resources\Talent\ApplicationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'user_id' => $this->user_id,
            'name'        => $this->name,
            'company_size' => $this->company_size,
            'country' => $this->country,
            'description' => $this->description,
            'logo_url' => $this->logo_url,
            'official_email' => $this->official_email,
            'onboarding_status' => $this->onboarding_status,
            'is_verified' => $this->is_verified,
            'slug' => $this->slug,
            'state' => $this->state,
            'status' => $this->status,
            'website_url' => $this->website_url,
            'total_applications' => $this->applications->count(),
            'applications' => ApplicationResource::collection(
                $this->whenLoaded('applications')
            ),
            'created_at'  => $this->created_at?->toDateTimeString(),
        ];
    }
}