<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateFile
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the media is a profile photo (you need to implement this logic)
        if (in_array($request->media->model_type, [\App\Models\Product::class])) {
            // If it's a profile photo, bypass authentication
            return $next($request);
        }

        // Otherwise, proceed with authentication middleware
        return app(Authenticate::class)->handle($request, $next);
    }
}
