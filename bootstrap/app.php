<?php

use App\Http\Middleware\AuthRateLimiter;
use App\Http\Middleware\RateLimit;
use App\Http\Middleware\VerifyAdminAuth;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Router;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        function (Router $router) {
            $router->middleware('api')
                ->prefix('api')
                ->group([
                    base_path('routes/api/auth.php'),
                    base_path('routes/api/user.php'),
                    base_path('routes/api/admin.php'),
                ]);
        },
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
