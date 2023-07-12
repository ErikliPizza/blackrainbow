<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'role' => $request->user()->role,
                    'avatar' => $request->user()->gravatar,
                    'instagram' => $request->user()->instagram,
                    'twitter' => $request->user()->twitter,
                    'nso' => $request->user()->nso,
                ] : null,
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'created' => [
                    'msg' => fn () => $request->session()->get('msg'),
                    'url' => fn () => $request->session()->get('url')
                ],
            ],
            'comp' => true,
            'filters'=> $request->only(['search', 'tag', 'style', 'order', 'author']),
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
        ]);
    }
}
