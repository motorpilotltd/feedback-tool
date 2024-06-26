<?php

namespace App\Http\Middleware;

use App\Traits\Livewire\WithDispatchNotify;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventBannedUsers
{
    use WithDispatchNotify;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->banned_at && ! $request->is('*/notification-bell')) {
            auth()->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', __('text.user:banned'));
        }

        return $next($request);
    }
}
