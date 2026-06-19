<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Authenticates the Laravel session from Azure App Service "Easy Auth" headers.
 *
 * When the app is deployed behind App Service Easy Auth, the platform performs
 * the Azure AD login a layer above PHP and injects trusted principal headers on
 * every request. This middleware trusts those headers to log the user in,
 * avoiding a second OAuth round-trip through Socialite (which loses deep links
 * across the redirects).
 *
 * SECURITY: these headers can be spoofed by any client that can reach the app
 * server directly. They are only trustworthy when Easy Auth genuinely sits in
 * front of the app and strips client-supplied copies. The services.azure.easy_auth
 * flag MUST therefore only be enabled on deployments that are actually fronted
 * by Easy Auth. When the flag is off this middleware is a complete no-op.
 */
class AuthenticateEasyAuth
{
    private const HEADER_PRINCIPAL_ID = 'X-MS-CLIENT-PRINCIPAL-ID';

    private const HEADER_PRINCIPAL_NAME = 'X-MS-CLIENT-PRINCIPAL-NAME';

    private const HEADER_PRINCIPAL = 'X-MS-CLIENT-PRINCIPAL';

    /**
     * Claim holding the Azure AD object id (oid). This is the value Socialite
     * stores in provider_user_id, so it — not the X-MS-CLIENT-PRINCIPAL-ID
     * header, which can differ by token version — is the authoritative key.
     */
    private const CLAIM_OBJECT_ID = 'http://schemas.microsoft.com/identity/claims/objectidentifier';

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only trust Easy Auth headers when the deployment is configured to be
        // behind Easy Auth. Off everywhere else, so headers can't be spoofed.
        if (! config('services.azure.easy_auth')) {
            return $next($request);
        }

        // Already authenticated via the Laravel session, nothing to do.
        if ($request->user()) {
            return $next($request);
        }

        $claims = $this->decodeClaims($request->header(self::HEADER_PRINCIPAL));

        // The AAD object id is what Socialite stored in provider_user_id. Prefer
        // the objectidentifier claim; fall back to the header for safety.
        $providerUserId = $this->claim($claims, [self::CLAIM_OBJECT_ID])
            ?? $request->header(self::HEADER_PRINCIPAL_ID);

        // No Easy Auth identity on this request (e.g. health checks). Let the
        // normal auth flow handle it.
        if (empty($providerUserId)) {
            return $next($request);
        }

        $email = $this->claim($claims, [
            'preferred_username',
            'email',
            'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress',
        ]) ?? $request->header(self::HEADER_PRINCIPAL_NAME);

        $name = $this->claim($claims, [
            'name',
            'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name',
        ]) ?? $email ?? $providerUserId;

        $user = User::updateOrCreate(
            ['provider_user_id' => $providerUserId],
            [
                'name' => $name,
                'email' => $email,
                'provider_platform' => 'azure',
            ],
        );

        Auth::login($user);

        return $next($request);
    }

    /**
     * Decode the base64 JSON X-MS-CLIENT-PRINCIPAL header into a list of claims.
     *
     * @return array<int, array{typ?: string, val?: string}>
     */
    private function decodeClaims(?string $header): array
    {
        if (empty($header)) {
            return [];
        }

        $decoded = json_decode((string) base64_decode($header, true), true);

        return is_array($decoded['claims'] ?? null) ? $decoded['claims'] : [];
    }

    /**
     * Return the value of the first claim matching one of the given types.
     *
     * @param  array<int, array{typ?: string, val?: string}>  $claims
     * @param  array<int, string>  $types
     */
    private function claim(array $claims, array $types): ?string
    {
        foreach ($claims as $claim) {
            if (in_array($claim['typ'] ?? null, $types, true) && ! empty($claim['val'])) {
                return $claim['val'];
            }
        }

        return null;
    }
}
