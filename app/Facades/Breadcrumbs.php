<?php

namespace App\Facades;

use App\Breadcrumbs\BreadcrumbsManager;
use Illuminate\Support\Facades\Facade;

class Breadcrumbs extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BreadcrumbsManager::class;
    }
}
