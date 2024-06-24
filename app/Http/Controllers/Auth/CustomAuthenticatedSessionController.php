<?php

namespace App\Http\Controllers\Auth;

use App\Settings\AzureADSettings;
use App\Settings\GeneralSettings;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

class CustomAuthenticatedSessionController extends AuthenticatedSessionController
{
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): LogoutResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

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

        if ($aadOnly && $forcelogin) {
            $request->session()->put('logged_out', true);
        }

        // Create a new LogoutResponse instance with the redirect URL
        return app(LogoutResponse::class);
    }
}
