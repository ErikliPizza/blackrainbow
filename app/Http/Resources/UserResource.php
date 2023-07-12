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
        $isFollowing = $this->followers->contains(auth()->id());

        return [
            'id' => $this->id,
            'name' => $this->name,
            'role' => $this->role,
            'arts_count' => $this->arts_count,
            'instagram' => $this->instagram,
            'twitter' => $this->twitter,
            'nso' => $this->nso,
            'avatar' => $this->gravatar,
            'created_at' => $this->created_at,
            'is_following' => $isFollowing,
        ];
    }
}
