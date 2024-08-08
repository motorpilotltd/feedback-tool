<?php

use App\Livewire\Admin\AdminUsersTable;

beforeEach(function () {
    setupData();
});

it('renders the AdminUsersTable component', function ($user) {
    login($user)
        ->livewire(AdminUsersTable::class)
        ->assertStatus(200);
})->with([
    'super admin logged in' => fn () => $this->userSuperAdmin,
    'product admin logged in' => fn () => $this->userProductAdmin1,
]);

it('displays Super Admin user in the table', function () {
    login($this->userSuperAdmin)
        ->livewire(AdminUsersTable::class)
        ->assertSee($this->userSuperAdmin->name)
        ->assertSee($this->userSuperAdmin->email);
});

it('displays Product Admin user in the table', function () {
    login($this->userSuperAdmin)
        ->livewire(AdminUsersTable::class)
        ->assertSee($this->userProductAdmin1->name)
        ->assertSee($this->userProductAdmin1->email);
});

it('can search for users by name or email', function () {
    login($this->userSuperAdmin)
        ->livewire(AdminUsersTable::class)
        ->set('searchSuperUser', $this->userSuperAdmin->name)
        ->assertSee($this->userSuperAdmin->email)
        ->assertDontSee($this->userSuperAdmin2->email)
        ->set('searchUser', $this->userProductAdmin1->email)
        ->assertSee($this->userProductAdmin1->name)
        ->assertDontSee($this->userProductAdmin2->name);
});

it('shows super admin user in the Super Admin table', function () {
    login($this->userSuperAdmin)
        ->livewire(AdminUsersTable::class)
        ->assertViewHas('superadmins', function ($superadmins) {
            return in_array($this->userSuperAdmin2->name, $superadmins->pluck('name')->toArray());
        })
        ->assertViewHas('superadmins', function ($superadmins) {
            return ! in_array($this->userProductAdmin1->name, $superadmins->pluck('name')->toArray());
        });
});

it('shows product admin user in the product Admin table', function () {
    login($this->userSuperAdmin)
        ->livewire(AdminUsersTable::class)
        ->assertViewHas('productadmins', function ($productadmins) {
            return in_array($this->userProductAdmin1->name, $productadmins->pluck('name')->toArray());
        })
        ->assertViewHas('productadmins', function ($productadmins) {
            return ! in_array($this->userSuperAdmin2->name, $productadmins->pluck('name')->toArray());
        });
});

it('can grant product admin role/permission to a user', function () {
    login($this->userSuperAdmin)
        ->livewire(AdminUsersTable::class)
        ->call('addRolePermissionModal')
        ->set('userId', $this->userBasic->id)
        ->set('roles', [config('const.ROLE_PRODUCT_ADMIN')])
        ->set('productIds', [$this->product1->id])
        ->call('save')
        ->assertViewHas('productadmins', function ($productadmins) {
            return in_array($this->userBasic->name, $productadmins->pluck('name')->toArray());
        });
});

it('can grant super admin role to a user', function () {
    login($this->userSuperAdmin)
        ->livewire(AdminUsersTable::class)
        ->call('addRolePermissionModal')
        ->set('userId', $this->userBasic->id)
        ->set('roles', [config('const.ROLE_SUPER_ADMIN')])
        ->call('save')
        ->assertViewHas('superadmins', function ($superadmins) {
            return in_array($this->userBasic->name, $superadmins->pluck('name')->toArray());
        });
});

it('can grant super admin and product admin role to a user', function () {
    login($this->userSuperAdmin)
        ->livewire(AdminUsersTable::class)
        ->call('addRolePermissionModal')
        ->set('userId', $this->userBasic->id)
        ->set('roles', [config('const.ROLE_SUPER_ADMIN')])
        ->call('save')
        ->assertViewHas('superadmins', function ($superadmins) {
            return in_array($this->userBasic->name, $superadmins->pluck('name')->toArray());
        })
        ->call('addRolePermissionModal')
        ->set('userId', $this->userBasic->id)
        ->set('roles', [config('const.ROLE_PRODUCT_ADMIN')])
        ->set('productIds', [$this->product1->id])
        ->call('save')
        ->assertViewHas('productadmins', function ($productadmins) {
            return in_array($this->userBasic->name, $productadmins->pluck('name')->toArray());
        });
});

it('can modify user permission', function () {
    login($this->userSuperAdmin)
        ->livewire(AdminUsersTable::class)
        ->call('addRolePermissionModal')
        ->set('userId', $this->userProductAdmin1->id)
        ->set('roles', [config('const.ROLE_SUPER_ADMIN')])
        ->set('productIds', [$this->product1->id])
        ->call('save')
        ->assertViewHas('superadmins', function ($superadmins) {
            return in_array($this->userProductAdmin1->name, $superadmins->pluck('name')->toArray());
        })
        ->assertViewHas('productadmins', function ($productadmins) {
            return ! in_array($this->userProductAdmin1->name, $productadmins->pluck('name')->toArray());
        });
});
