<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArtResource;
use App\Http\Resources\UserResource;
use App\Models\Art;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
class HomeController extends Controller
{
    public function index(Request $request)
    {
        $arts = Art::searchWithFiltersAndOrder($request);

        if ($request->wantsJson()) {
            return ArtResource::collection($arts);
        }
        $lastWeek = Carbon::now()->subWeek();
        $startDate = Carbon::now()->startOfWeek();
        $endDate = Carbon::now()->endOfWeek();


        // want only arts while searching !! IMPORTANT !! you can delete the user before send the request to check if it is working or not
        return Inertia::render('Welcome', [
            // ALWAYS included on first visit...
            // OPTIONALLY included on partial reloads...
            // ALWAYS evaluated...
            'arts' => ArtResource::collection($arts),
            // ALWAYS included on first visit...
            // OPTIONALLY included on partial reloads...
            // ONLY evaluated when needed...
            'popularArts' => fn () => ArtResource::collection(
                Art::withCount('likes')
                    ->whereHas('likes', function ($query) use ($lastWeek) {
                        $query->where('likes.created_at', '>=', $lastWeek);
                    })
                    ->orderByDesc('likes_count')
                    ->take(5)
                    ->get()
                    ->whenEmpty(function ($collection) {
                        return Art::withCount('likes')
                            ->orderByDesc('likes_count')
                            ->take(5)
                            ->get();
                    })
            ),
            // ALWAYS included on first visit...
            // OPTIONALLY included on partial reloads...
            // ONLY evaluated when needed...
            'mostUser' => UserResource::make(
                User::withCount('arts')
                    ->whereHas('arts', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('created_at', [$startDate, $endDate]);
                    })
                    ->orderByDesc('arts_count')
                    ->first() ?? User::withCount('arts')
                ->orderByDesc('arts_count')
                ->first()
            ),
        ]);
    }
}
