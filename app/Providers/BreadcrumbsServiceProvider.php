<?php

namespace App\Providers;

use App\Breadcrumbs\BreadcrumbsManager;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class BreadcrumbsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(BreadcrumbsManager::class);

        $loader = AliasLoader::getInstance();
        $loader->alias('Breadcrumbs', \App\Facades\Breadcrumbs::class);
    }

    public function boot(): void
    {
        $file = config('breadcrumbs.files');

        if ($file && is_file($file)) {
            require $file;
        }
    }
}
