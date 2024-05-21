<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthenticateFile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the media is a profile photo (you need to implement this logic)
        if (in_array($request->media->model_type, ['App\Models\Product'])) {
            // If it's a profile photo, bypass authentication
            return $next($request);
        }

        // Otherwise, proceed with authentication middleware
        return app(Authenticate::class)->handle($request, $next);
    }
}
