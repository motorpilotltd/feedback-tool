<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Fortify\Http\Responses\LoginResponse;

class LogLogin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): LoginResponse
    {
        // Logged after authentication using the user id, not the submitted email,
        // to keep personal data out of the application logs.
        $response = $next($request);

        Log::info('User logged in', ['user_id' => auth()->id()]);

        return $response;
    }
}
