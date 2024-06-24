<?php

namespace Database\Seeders;

use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        try {
            $permission = config('const.PERMISSION_SYSTEM_MANAGE');
            $permission2 = config('const.PERMISSION_PRODUCTS_MANAGE');

            Permission::create(['name' => $permission]);
            Permission::create(['name' => $permission2]);

            $superAdminRole = Role::create(['name' => config('const.ROLE_SUPER_ADMIN')]);
            $productAdminRole = Role::create(['name' => config('const.ROLE_PRODUCT_ADMIN')]);

            $superAdminRole->givePermissionTo([
                $permission,
            ]);

            $productAdminRole->givePermissionTo([
                $permission2,
            ]);

            // Assign app admin a super-admin role
            $user = User::where('email', config('const.ADMIN_EMAIL'))->get()->first();
            if (! $user) {
                $user = User::factory()->create([
                    'name' => 'Admin',
                    'email' => config('const.ADMIN_EMAIL'),
                ]);
            }
            $user->assignRole(config('const.ROLE_SUPER_ADMIN'));
        } catch (Exception $e) {
            // Any ..AlreadyExists Execeptions
        }
    }
}
