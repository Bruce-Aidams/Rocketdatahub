<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);

        // Use the new caching mechanism for settings
        $maintenanceMode = \App\Models\Setting::getCached('maintenance_mode');
        $m1 = microtime(true);

        $message = \App\Models\Setting::getCached('site_alert_message', 'System is currently under maintenance. Please try again later.');
        $m2 = microtime(true);

        // LOG TIMING (only if still unusually slow)
        if (($m2 - $start) > 0.05) {
            \Illuminate\Support\Facades\Log::debug("CheckMaintenanceMode CACHED Time: " . ($m2 - $start) . "s (Q1: " . ($m1 - $start) . "s, Q2: " . ($m2 - $m1) . "s)");
        }

        // If maintenance mode is not enabled, continue normally
        if ($maintenanceMode != '1' && $maintenanceMode != 'true') {
            return $next($request);
        }

        // Allow admin users to bypass maintenance mode
        if ($request->user() && strtolower($request->user()->role) === 'admin') {
            return $next($request);
        }

        // Allow login/admin routes so admins can actually log in
        if ($request->is('login') || $request->is('login/*') || $request->is('admin/login') || $request->is('logout')) {
            return $next($request);
        }

        // Maintenance mode is active - handle response
        if ($request->expectsJson()) {
            return response()->json([
                'status' => false,
                'message' => $message,
                'maintenance' => true
            ], 503);
        }

        // Log out non-admin user if logged in
        if ($request->user()) {
            \Illuminate\Support\Facades\Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return response()->view('maintenance', [
            'message' => $message
        ], 503);
    }
}
