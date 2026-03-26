<?php

use App\Providers\AppServiceProvider;
use App\Providers\BreadcrumbsServiceProvider;
use App\Providers\FortifyServiceProvider;
use App\Providers\JetstreamServiceProvider;

return [
    AppServiceProvider::class,
    BreadcrumbsServiceProvider::class,
    FortifyServiceProvider::class,
    JetstreamServiceProvider::class,
];
