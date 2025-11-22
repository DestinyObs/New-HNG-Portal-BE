<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => (string) $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'othername' => $this->othername,
            'email' => $this->email,
            'phone' => $this->phone,
            'dob' => $this->dob ? $this->dob->toDateString() : null,
            'status' => $this->status,
            'address_id' => $this->address_id,
            'photo_url' => $this->photo_url,
            'google_id' => $this->google_id,
            'google_token_expires_at' => $this->google_token_expires_at ? $this->google_token_expires_at->toIso8601String() : null,
            'role' => $this->role,
            'roles' => $this->when($this->relationLoaded('roles'), fn() => $this->roles->pluck('name')),
            'permissions' => $this->when($this->relationLoaded('permissions'), fn() => $this->permissions->pluck('name')),
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : null,
        ];
    }
}
