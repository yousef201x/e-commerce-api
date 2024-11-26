<?php

use Illuminate\Foundation\Application;
use Illuminate\Routing\Router;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(function (Router $router) {
        // API Routes
        $router->middleware('api')
            ->prefix('api')
            ->group(function () {
                require base_path('routes/api/auth.php');
                require base_path('routes/api/user.php');
                require base_path('routes/api/admin.php');
            });

        // Web Routes
        $router->group([], function () {
            require base_path('routes/web.php');
        });
    })
    ->withMiddleware(function ($middleware) {
        // Add global middleware or modify existing ones if necessary
    })
    ->withExceptions(function ($exceptions) {
        // Handle global exceptions if necessary
    })
    ->create();
