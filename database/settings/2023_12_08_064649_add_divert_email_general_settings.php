<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.divert_email', '');
        $this->migrator->add('general.enable_divert_email', false);
    }
};
