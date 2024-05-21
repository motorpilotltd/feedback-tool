<?php

use phpDocumentor\Reflection\Types\Boolean;

it('can show login form page with default settings', function () {
    $this->get(route('login'))
        ->assertSee('login-form')
        ->assertSee(__('text.form:email'))
        ->assertSee(__('text.form:password'))
        ->assertSee(__('text.form:login'));
});

it('can show/hide login with org for aad_enable settings', function (bool $isEnable) {
    setupAzureSettings();
    $this->azureSettings->aad_enable = $isEnable;
    $request = $this->get(route('login'));
    if ($isEnable) {
        $request->assertSee(__('text.form:loginwithorg'));
    } else {
        $request->assertDontSee(__('text.form:loginwithorg'));
    }
})->with([
    'enabled' => true,
    'disabled' => false
]);

it('redirects/not redirect to Azure AD login when aad_only', function ($isEnable) {
    setupAzureSettings();
    $this->azureSettings->aad_only = $isEnable;
    $request = $this->get(route('login'));
    if ($isEnable) {
        $request->assertStatus(302);
        $request->assertRedirect(route('auth.microsoft'));
    } else {
        $request->assertStatus(200);
    }
})->with([
    'enabled' => true,
    'disabled' => false
]);
