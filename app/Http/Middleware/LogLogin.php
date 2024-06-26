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
        Log::info('This user is logging in: '.$request->email);

        return $next($request);
    }
}
