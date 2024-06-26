<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class LinksSettings extends Settings
{
    public array $links;

    public string $title;

    public static function group(): string
    {
        return 'links';
    }
}
