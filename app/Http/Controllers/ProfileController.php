<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
class ProfileController extends Controller
{

    public function index(User $user)
    {
        $tags = $user->arts()
            ->join('art_tag', 'arts.id', '=', 'art_tag.art_id')
            ->join('tags', 'art_tag.tag_id', '=', 'tags.id')
            ->select('tags.name', DB::raw('COUNT(*) as count'))
            ->groupBy('tags.name')
            ->orderBy('count', 'desc')
            ->limit(3) // Retrieve the top 5 most used tags
            ->get();

        $styles = $user->arts()
            ->join('art_style', 'arts.id', '=', 'art_style.art_id')
            ->join('styles', 'art_style.style_id', '=', 'styles.id')
            ->select('styles.name', DB::raw('COUNT(*) as count'))
            ->groupBy('styles.name')
            ->orderBy('count', 'desc')
            ->limit(3) // Retrieve the top 5 most used styles
            ->get();

        $totalArticles = $user->arts()->count();
        $totalComments = $user->comments()->count();
        $totalLikes = $user->likedArts()->count();
        $followers = $user->followers()->count();
        $following = $user->following()->count();

        return Inertia::render('Profile/Show', [
            'user' => UserResource::make($user),
            'tags' => $tags,
            'styles' => $styles,
            'articles' => $totalArticles,
            'comments' => $totalComments,
            'likes' => $totalLikes,
            'followers' => $followers,
            'following' => $following
        ]);
    }


    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = array_filter($request->validated());

        $nullableFields = ['instagram', 'twitter', 'nso'];

        foreach ($nullableFields as $field) {
            if (!isset($data[$field])) {
                $user->$field = null;
            }
        }

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'updated!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
