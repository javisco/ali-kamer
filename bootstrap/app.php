<?php

use App\Http\Middleware\EnsureShopActive;
use App\Http\Middleware\EnsureUserHasRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role'              => EnsureUserHasRole::class,
            'shop.active'       => EnsureShopActive::class,
            'check.status'      => \App\Http\Middleware\CheckAccountStatus::class,
            'check.restriction' => \App\Http\Middleware\CheckSanctionRestrictions::class,
        ]);
        // Appliquer check.status sur toutes les routes web authentifiées
        $middleware->appendToGroup('web', [
            \App\Http\Middleware\CheckAccountStatus::class,
        ]);
        $middleware->appendToGroup('api', [
            \App\Http\Middleware\CheckAccountStatus::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'webhooks/elgiopay',
            'didit/webhook',
            'api/*',
        ]);
    })->withMiddleware(function (Middleware $middleware) {
        // Indique à Laravel de faire confiance à Ngrok
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
