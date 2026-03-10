<?php

use App\Http\Middleware\AuthenticateFile;
use App\Http\Middleware\CheckAppSettings;
use App\Http\Middleware\EnsurePasswordChanged;
use App\Http\Middleware\EnsureProductNotArchived;
use App\Http\Middleware\PreventBannedUsers;
use App\Providers\AppServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Laravel\Jetstream\Http\Middleware\AuthenticateSession;
use SocialiteProviders\Manager\ServiceProvider;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        ServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        // channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo(AppServiceProvider::HOME);

        $middleware->trustHosts();

        $middleware->trustProxies(
            at: '*',
            headers: Request::HEADER_X_FORWARDED_FOR |
                Request::HEADER_X_FORWARDED_PROTO
        );

        $middleware->web([
            AuthenticateSession::class,
            PreventBannedUsers::class,
            EnsurePasswordChanged::class,
            CheckAppSettings::class,
        ]);

        $middleware->statefulApi();
        $middleware->throttleApi();
        $middleware->api(PreventBannedUsers::class);

        $middleware->alias([
            'authFile' => AuthenticateFile::class,
            'ensureProductNotArchived' => EnsureProductNotArchived::class,
            'permission' => PermissionMiddleware::class,
            'role' => RoleMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
