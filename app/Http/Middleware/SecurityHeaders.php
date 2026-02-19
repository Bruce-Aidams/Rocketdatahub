<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Basic Security Headers
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Content Security Policy
        $isProduction = config('app.env') === 'production';

        if (!$isProduction) {
            // Very permissive for local development but with basic safety
            $csp = "default-src 'self'; ";
            $csp .= "script-src * 'unsafe-inline' 'unsafe-eval' data: blob:; ";
            $csp .= "style-src * 'unsafe-inline'; ";
            $csp .= "img-src * data: blob:; ";
            $csp .= "connect-src * 'unsafe-inline' 'unsafe-eval' ws: wss:; ";
            $csp .= "frame-src *; ";
            $csp .= "object-src 'none'; ";
        } else {
            // Strict for production
            $csp = "upgrade-insecure-requests; ";
            $csp .= "default-src 'self'; ";
            $csp .= "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://unpkg.com https://js.paystack.co; ";
            $csp .= "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net; ";
            $csp .= "font-src 'self' https://fonts.gstatic.com data:; ";
            $csp .= "img-src 'self' data: https:; ";
            $csp .= "connect-src 'self' https://api.paystack.co; ";
            $csp .= "frame-src 'self' https://js.paystack.co; ";
            $csp .= "object-src 'none'; ";
            $csp .= "require-trusted-types-for 'script';";

            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
            $response->headers->set('X-Content-Type-Options', 'nosniff');
        }

        $response->headers->set('Content-Security-Policy', $csp);
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), browsing-topics=()');

        return $response;
    }
}
