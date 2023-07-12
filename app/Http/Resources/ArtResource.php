<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ArtResource extends JsonResource
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
            'title' => $this->title,
            'likes' => number_format($this->likes->count()),
            'liked' => $this->likes->contains('id', Auth::id()),
            'views' => number_format($this->views),
            'comments' => number_format($this->comments->count()),
            'description' => $this->description,
            'hours_spent' => $this->hours_spent,
            'created_at' => $this->created_at,
            'tags' => $this->tags()->select('name')->take(4)->get()->pluck('name'),
            'styles' => $this->styles()->select('name')->take(4)->get()->pluck('name'),
            'uri' => '/arts/' . $this->id,
            'author' => UserResource::make($this->user),
            'images' => $this->images()->select('filename', 'id')->get(),
            'can' => [
                'edit' => auth()->check() && auth()->user()->can('update', $this->resource),
            ]
        ];

        // Include the description and other specific fields for showing a particular article
        /*if ($request->route()->getName() === 'arts.show'
            ||
            $request->route()->getName() === 'arts.likedIndex'
            ||
            $request->route()->getName() === 'home'
            ||
            $request->route()->getName() === 'arts'
            )
        {
            $resource['description'] = $this->description;
            $resource['hours_spent'] = $this->hours_spent;
            $resource['created_at'] = $this->created_at;
            $resource['tags'] = $this->tags()->select('name')->take(4)->get()->pluck('name');
            $resource['styles'] = $this->styles()->select('name')->take(4)->get()->pluck('name');
            $resource['uri'] = '/arts/' . $this->id;
            $resource['author'] = UserResource::make($this->user);
            $resource['can'] = [
                'edit' => auth()->check() && auth()->user()->can('update', $this->resource),
            ];
        }
        return $resource;*/
    }
}
