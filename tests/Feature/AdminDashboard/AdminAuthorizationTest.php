<?php

use App\Livewire\Admin\AdminUsersTable;
use App\Livewire\Admin\AllUsersTable;
use App\Livewire\Admin\BannedUsersTable;

beforeEach(function () {
    setupData();
});

it('forbids a product admin from granting the super-admin role', function () {
    login($this->userProductAdmin1)
        ->livewire(AdminUsersTable::class)
        ->set('userId', $this->userBasic->id)
        ->set('roles', [config('const.ROLE_SUPER_ADMIN')])
        ->call('save')
        ->assertForbidden();

    expect($this->userBasic->fresh()->hasRole(config('const.ROLE_SUPER_ADMIN')))->toBeFalse();
});

it('forbids a product admin from revoking a super-admin', function () {
    login($this->userProductAdmin1)
        ->livewire(AdminUsersTable::class)
        ->call('revokeDialog', $this->userSuperAdmin2->id, config('const.ROLE_SUPER_ADMIN'), null, true)
        ->assertForbidden();

    expect($this->userSuperAdmin2->fresh()->hasRole(config('const.ROLE_SUPER_ADMIN')))->toBeTrue();
});

it('lets a super admin grant the super-admin role', function () {
    login($this->userSuperAdmin)
        ->livewire(AdminUsersTable::class)
        ->set('userId', $this->userBasic->id)
        ->set('roles', [config('const.ROLE_SUPER_ADMIN')])
        ->call('save')
        ->assertOk();

    expect($this->userBasic->fresh()->hasRole(config('const.ROLE_SUPER_ADMIN')))->toBeTrue();
});

it('forbids a product admin from suspending a user', function () {
    login($this->userProductAdmin1)
        ->livewire(BannedUsersTable::class)
        ->set('userId', $this->userBasic->id)
        ->call('suspendUser')
        ->assertForbidden();

    expect($this->userBasic->fresh()->banned_at)->toBeNull();
});

it('lets a super admin suspend a user', function () {
    login($this->userSuperAdmin)
        ->livewire(BannedUsersTable::class)
        ->set('userId', $this->userBasic->id)
        ->call('suspendUser')
        ->assertOk();

    expect($this->userBasic->fresh()->banned_at)->not->toBeNull();
});

it('forbids a product admin from creating a user', function () {
    login($this->userProductAdmin1)
        ->livewire(AllUsersTable::class)
        ->set('name', 'Mallory')
        ->set('email', 'mallory@example.com')
        ->call('save')
        ->assertForbidden();

    $this->assertDatabaseMissing('users', ['email' => 'mallory@example.com']);
});
