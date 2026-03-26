<?php

namespace App\Providers;

use App\Breadcrumbs\BreadcrumbsManager;
use App\Facades\Breadcrumbs;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class BreadcrumbsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(BreadcrumbsManager::class);

        $loader = AliasLoader::getInstance();
        $loader->alias('Breadcrumbs', Breadcrumbs::class);
    }

    public function boot(): void
    {
        $file = config('breadcrumbs.files');

        if ($file && is_file($file)) {
            require $file;
        }
    }
}
