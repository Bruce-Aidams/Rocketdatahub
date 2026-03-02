<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifiedUserOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Allow access if user is verified or if it's an admin (admins are trusted)
        if ($user && ($user->is_verified || $user->role === 'admin')) {
            return $next($request);
        }

        // Allow access to the verification notice, request, and logout routes
        if ($request->routeIs('verification.notice') || $request->routeIs('verification.request') || $request->routeIs('logout')) {
            return $next($request);
        }

        // Redirect unverified users
        return redirect()->route('verification.notice');
    }
}
