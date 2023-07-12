<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Check if the authenticated user has the required role
        if (Auth::check() && Auth::user()->role === $role) {
            return $next($request);
        }

        // Redirect or return a response indicating access denied
        return Inertia::render('Error', ['status' => 403])
            ->toResponse($request)
            ->setStatusCode(403);
    }
}
