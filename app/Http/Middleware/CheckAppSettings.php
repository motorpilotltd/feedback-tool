<?php

namespace App\Http\Middleware;

use App\Traits\Livewire\WithAuthRedirects;
use App\Settings\AzureADSettings;
use App\Settings\GeneralSettings;
use Closure;
use Exception;
use Illuminate\Http\Request;

class CheckAppSettings
{
    use WithAuthRedirects;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) { // Check if user was authenticated
            try {
                $forcelogin = app(GeneralSettings::class)->forcelogin;
            } catch (Exception $e) {
                $forcelogin = false;
            }

            // Redirect to /login if forcelogin was enabled

            $exclude = collect([
                'auth.*',
                'login',
                'login.*',
                'password.*',
                'register',
                'register.*'
            ]);

            if (!$request->routeIs($exclude) && $forcelogin) {
                return $this->redirectToLogin();
            }

            // Redirect login to AAD login when aad_only was enabled
            try {
                $aadOnly = app(AzureADSettings::class)->aad_only;
            } catch (Exception $e) {
                $aadOnly = false;
            }
            if ($request->routeIs('login') && $request->isMethod('get') && $aadOnly) {
                return $this->redirectToAzureLogin();
            }
        }
        return $next($request);
    }
}
