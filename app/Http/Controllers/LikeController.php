<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Art;
use App\Models\User;
class LikeController extends Controller
{
    public function like(Art $art)
    {
        if (auth()->check()) {
            $user = auth()->user();
            $user->likedArts()->toggle($art);
        }
    }

    public function likes(Art $art)
    {
        $users = $art->likes()->take(85)->get();
        return response()->json(['users' => UserResource::collection($users)]);
    }
}
