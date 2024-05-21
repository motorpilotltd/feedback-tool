<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class RenamePropertiesAzureADSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->rename('azure.return_url', 'azure.redirect');
        $this->migrator->rename('azure.directory', 'azure.tenant');
    }
}
