<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustHosts(function ($request) {
            return [
                'localhost',       // Trust requests coming from localhost
                '127.0.0.1',       // Trust requests from local IP address
                '192.168.1.245',     // Trust requests coming from this device
            ];
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
