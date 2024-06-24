<?php

namespace App\Http\Middleware;

use App\Settings\AzureADSettings;
use App\Settings\GeneralSettings;
use App\Traits\Livewire\WithAuthRedirects;
use Closure;
use Exception;
use Illuminate\Http\Request;

class CheckAppSettings
{
    use WithAuthRedirects;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $forcelogin = app(GeneralSettings::class)->forcelogin;
        } catch (Exception $e) {
            $forcelogin = false;
        }

        // Redirect login to AAD login when aad_only was enabled
        try {
            $aadOnly = app(AzureADSettings::class)->aad_only;
        } catch (Exception $e) {
            $aadOnly = false;
        }

        if (! auth()->check()) { // Check if user was authenticated
            // Redirect to /login if forcelogin was enabled
            $exclude = collect([
                'auth.*',
                'login',
                'login.*',
                'password.*',
                'register',
                'register.*',
            ]);
            if ($request->routeIs('login') && $request->get('login') === 'show') {
                return $next($request);
            } else {
                if (! $request->routeIs($exclude) && $forcelogin) {
                    return $this->redirectToLogin();
                }

                if ($request->routeIs('login') && $request->isMethod('get') && $aadOnly && ! session()->get('logged_out', false)) {
                    return $this->redirectToAzureLogin();
                }
                session()->pull('logged_out');
            }
        }

        return $next($request);
    }
}
