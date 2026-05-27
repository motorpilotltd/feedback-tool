<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Create the initial super-admin account.
     *
     * The password is taken from the ADMIN_INITIAL_PASSWORD environment
     * variable when set; otherwise a unique strong password is generated and
     * printed once to the console. Either way the account is flagged so the
     * EnsurePasswordChanged middleware forces a password change on first login.
     *
     * Requires roles to exist — run RoleAndPermissionSeeder first.
     */
    public function run(): void
    {
        $email = config('const.ADMIN_EMAIL');

        $user = User::where('email', $email)->first();

        if (! $user) {
            $envPassword = env('ADMIN_INITIAL_PASSWORD');
            $password = $envPassword ?: Str::password(20);

            $user = new User;
            $user->name = 'Administrator';
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->email_verified_at = now();
            $user->must_change_password = true;
            $user->save();

            $this->reportCredentials($email, $password, (bool) $envPassword);
        } else {
            $this->command?->info("Admin user [{$email}] already exists — ensuring super-admin role.");
        }

        // Idempotent, and repairs an existing admin account that lost the role.
        $user->assignRole(config('const.ROLE_SUPER_ADMIN'));
    }

    /**
     * Report the new account's credentials to whoever ran the seeder.
     */
    private function reportCredentials(string $email, string $password, bool $fromEnv): void
    {
        if (! $this->command) {
            return;
        }

        if ($fromEnv) {
            $this->command->info("Admin user [{$email}] created with the password from ADMIN_INITIAL_PASSWORD.");

            return;
        }

        $line = str_repeat('=', 64);
        $this->command->warn($line);
        $this->command->warn('  INITIAL ADMIN ACCOUNT CREATED');
        $this->command->warn('  Email:    '.$email);
        $this->command->warn('  Password: '.$password);
        $this->command->warn('  Shown ONCE only — store it now. The admin must change');
        $this->command->warn('  this password on first login.');
        $this->command->warn($line);
    }
}
