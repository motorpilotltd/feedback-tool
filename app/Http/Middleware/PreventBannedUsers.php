<?php

namespace App\Http\Middleware;

use App\Traits\Livewire\WithDispatchNotify;
use Closure;
use Illuminate\Http\Request;

class PreventBannedUsers
{
    use WithDispatchNotify;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
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
