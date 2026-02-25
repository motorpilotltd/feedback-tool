<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Livewire\Livewire;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordChanged
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ($user->getAttributes()['must_change_password'] ?? false)) {
            $allowedRoutes = ['profile.show', 'user-password.update', 'logout'];

            if (Livewire::isLivewireRequest() || $request->routeIs(...$allowedRoutes)) {
                return $next($request);
            }

            session()->flash('flash.banner', __('text.mustchangepassword'));
            session()->flash('flash.bannerStyle', 'danger');

            return redirect()->route('profile.show');
        }

        return $next($request);
    }
}
