<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateAzureADSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('azure.client_id', '');
        $this->migrator->add('azure.client_secret', '');
        $this->migrator->add('azure.return_url', '');
        $this->migrator->add('azure.aad_only', false);
        $this->migrator->add('azure.aad_enable', false);
        $this->migrator->add('azure.directory', '');
    }
}
