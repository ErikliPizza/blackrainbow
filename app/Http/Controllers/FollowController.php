<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;

class FollowController extends Controller
{
    public function toggleFollow(User $user)
    {
        $loggedInUser = auth()->user();

        $loggedInUser->following()->toggle($user);
    }

    public function followers(User $user)
    {
        $followers = $user->followers;
        return response()->json(['users' => UserResource::collection($followers)]);
    }

    public function followings(User $user)
    {
        $followings = $user->following;
        return response()->json(['users' => UserResource::collection($followings)]);
    }

}
