<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Seed the application's roles and permissions.
     *
     * Uses findOrCreate so the seeder is safe to run repeatedly. The admin
     * account itself is created separately by AdminUserSeeder.
     */
    public function run(): void
    {
        $systemPermission = Permission::findOrCreate(config('const.PERMISSION_SYSTEM_MANAGE'));
        $productsPermission = Permission::findOrCreate(config('const.PERMISSION_PRODUCTS_MANAGE'));

        Role::findOrCreate(config('const.ROLE_SUPER_ADMIN'))
            ->givePermissionTo($systemPermission);

        Role::findOrCreate(config('const.ROLE_PRODUCT_ADMIN'))
            ->givePermissionTo($productsPermission);
    }
}
