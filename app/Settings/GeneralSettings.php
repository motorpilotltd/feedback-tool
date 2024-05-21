<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{

    public string $title;
    public string $welcome_title;
    public string $welcome_description;
    public string $language;
    public string $app_email;
    public int $pagination;
    public string $smtp_host;
    public string $smtp_port;
    public string $smtp_username;
    public string $smtp_password;
    public bool $forcelogin;
    public string $ga_measurement_id;
    public string $divert_email;
    public bool $enable_divert_email;

    public static function group(): string
    {
        return 'general';
    }
}
