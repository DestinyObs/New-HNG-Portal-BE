<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'othername' => $this->othername,
            "email" => $this->email,
            "current_role" => $this->current_role,
            "photo_url" => $this->photo_url,
            "email_verification" => !is_null($this->email_verified_at),
            "updated_at" => $this->updated_at,
            "created_at" => $this->created_at,

            "company" => $this->whenLoaded('company'),
            "skills" => $this->whenLoaded('skills'),
            "experiences" => $this->whenLoaded('experiences'),
            "verification" => $this->whenLoaded('verification'),
            "preferences" => $this->whenLoaded('preferences'),
            // "jobs" => $this->whenLoaded('jobs'),
            "bio" => $this->whenLoaded('bio'),
        ];
    }
}
