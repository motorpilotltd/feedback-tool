<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class AzureADSettings extends Settings
{
    public string $client_id;

    public string $client_secret;

    public string $redirect;

    public bool $aad_only;

    public bool $aad_enable;

    public string $tenant;

    public static function group(): string
    {
        return 'azure';
    }
}
