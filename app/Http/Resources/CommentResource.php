<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'content' => $this->content,
            'created_at' => $this->created_at,
            'author' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'role' => $this->user->role,
                'avatar' => $this->user->gravatar,
                // Include any other user attributes you need
            ],
            'can' => [
                'destroy' => auth()->check() && auth()->user()->can('delete', $this->resource),
            ]
        ];
    }
}
