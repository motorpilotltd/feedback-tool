<?php

use App\Livewire\User\LoginAs;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;

it('forbids a non super-admin from impersonating another user', function () {
    $actor = User::factory()->create();
    $target = User::factory()->create();

    login($actor)
        ->livewire(LoginAs::class, ['user' => $target])
        ->call('loginUser', $target->id)
        ->assertForbidden();

    expect(auth()->id())->toBe($actor->id);
});

it('lets a super admin impersonate another user', function () {
    $this->seed(RoleAndPermissionSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole(config('const.ROLE_SUPER_ADMIN'));
    $target = User::factory()->create();

    login($admin)
        ->livewire(LoginAs::class, ['user' => $target])
        ->call('loginUser', $target->id)
        ->assertOk();

    expect(auth()->id())->toBe($target->id);
});
