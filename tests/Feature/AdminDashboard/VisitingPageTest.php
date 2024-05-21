<?php

use Laravel\Jetstream\Http\Livewire\NavigationMenu;
use function Pest\Livewire\livewire;

beforeEach(function () {
    setupData();
});

it('throws unauthorized status for users without admin permission', function () {
    login($this->userBasic);
    $this->get(route('admin.dashboard'))
        ->assertStatus(403);
});

it('redirects to login page when no user logged in', function () {
    $this->get(route('admin.dashboard'))
        ->assertStatus(302)
        ->assertRedirect('/login');
});

it('can authorized user with "admin" permission', function ($user) {
    login($user);
    $this->get(route('admin.dashboard'))
        ->assertStatus(200);
})->with([
    'super admin' => fn() => $this->userSuperAdmin,
    'product admin' => fn() => $this->userProductAdmin1,
]);


it('expects Products and Settings links', function ($userType) {
    $user = $userType == 'superAdmin' ? $this->userSuperAdmin : $this->userProductAdmin1 ;
    login($user);
    $component = livewire(NavigationMenu::class);

    $assertion = $userType == 'superAdmin' ? 'assertSee' : 'assertDontSee';
    $component->$assertion(route('admin.products'));
    $component->$assertion(route('admin.settings'));
})->with([
    'to show when user is super admin' => ['superadmin'],
    'not to show when user is product admin' => ['productadmin']
]);
