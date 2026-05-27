<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Reference data the application needs to function. Safe for every
        // environment — no factories, no Faker, idempotent on re-run.
        $this->call([
            StatusesSeeder::class,
            RoleAndPermissionSeeder::class,
            AdminUserSeeder::class,
        ]);

        // Sample/demo data for local development only. It uses model factories,
        // which depend on the dev-only fakerphp/faker package and so cannot run
        // on deployed environments (where Composer installs with --no-dev).
        if (app()->environment('local')) {
            $this->call(DemoDataSeeder::class);
        }
    }
}
