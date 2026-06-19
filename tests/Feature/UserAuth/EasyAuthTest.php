<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Build a base64-encoded X-MS-CLIENT-PRINCIPAL header like Easy Auth injects.
 *
 * @param  array<int, array{typ: string, val: string}>  $claims
 */
function easyAuthPrincipal(array $claims): string
{
    return base64_encode(json_encode([
        'auth_typ' => 'aad',
        'claims' => $claims,
    ]));
}

function easyAuthHeaders(string $id, string $email, ?string $name = null): array
{
    return [
        // Deliberately differs from the objectidentifier claim: the claim is
        // authoritative for provider_user_id, the header is only a fallback.
        'X-MS-CLIENT-PRINCIPAL-ID' => 'header-'.$id,
        'X-MS-CLIENT-PRINCIPAL-NAME' => $email,
        'X-MS-CLIENT-PRINCIPAL' => easyAuthPrincipal(array_filter([
            ['typ' => 'http://schemas.microsoft.com/identity/claims/objectidentifier', 'val' => $id],
            ['typ' => 'preferred_username', 'val' => $email],
            $name ? ['typ' => 'name', 'val' => $name] : null,
        ])),
    ];
}

it('logs the user in from Easy Auth headers when the flag is enabled', function () {
    config(['services.azure.easy_auth' => true]);

    $this->withHeaders(easyAuthHeaders('oid-123', 'jane@example.com', 'Jane Doe'))
        ->get('/')
        ->assertOk();

    $this->assertAuthenticated();
    $user = User::firstWhere('provider_user_id', 'oid-123');
    expect($user)->not->toBeNull()
        ->and($user->email)->toBe('jane@example.com')
        ->and($user->name)->toBe('Jane Doe')
        ->and($user->provider_platform)->toBe('azure');
});

it('reuses the existing account matched by principal id', function () {
    config(['services.azure.easy_auth' => true]);

    $existing = User::factory()->create([
        'provider_user_id' => 'oid-123',
        'provider_platform' => 'azure',
        'email' => 'old@example.com',
    ]);

    $this->withHeaders(easyAuthHeaders('oid-123', 'new@example.com', 'Jane Doe'))
        ->get('/')
        ->assertOk();

    $this->assertAuthenticatedAs($existing->fresh());
    expect(User::where('provider_user_id', 'oid-123')->count())->toBe(1)
        ->and($existing->fresh()->email)->toBe('new@example.com');
});

it('ignores Easy Auth headers when the flag is disabled', function () {
    config(['services.azure.easy_auth' => false]);

    $this->withHeaders(easyAuthHeaders('oid-123', 'jane@example.com'))
        ->get('/')
        ->assertOk();

    $this->assertGuest();
    expect(User::where('provider_user_id', 'oid-123')->exists())->toBeFalse();
});

it('falls back to the principal id header when the objectidentifier claim is absent', function () {
    config(['services.azure.easy_auth' => true]);

    $this->withHeaders([
        'X-MS-CLIENT-PRINCIPAL-ID' => 'header-only-id',
        'X-MS-CLIENT-PRINCIPAL-NAME' => 'jane@example.com',
        'X-MS-CLIENT-PRINCIPAL' => easyAuthPrincipal([
            ['typ' => 'preferred_username', 'val' => 'jane@example.com'],
        ]),
    ])->get('/')->assertOk();

    $this->assertAuthenticated();
    expect(User::where('provider_user_id', 'header-only-id')->exists())->toBeTrue();
});

it('does nothing when no principal header is present', function () {
    config(['services.azure.easy_auth' => true]);

    $this->get('/')->assertOk();

    $this->assertGuest();
});
