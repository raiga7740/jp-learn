<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();

// Safeguard for shared hosting environments (such as InfinityFree) where composer.json might be omitted
try {
    $reflector = new ReflectionProperty($app, 'namespace');
    $reflector->setAccessible(true);
    if (! $reflector->getValue($app)) {
        $reflector->setValue($app, 'App\\');
    }
} catch (\Throwable $e) {
    // Gracefully continue if reflection is restricted
}

// In addition, ensure a fallback composer.json exists in basePath if it was omitted during deployment
try {
    $composerPath = $app->basePath('composer.json');
    if (! file_exists($composerPath)) {
        @file_put_contents($composerPath, json_encode([
            'name' => 'laravel/laravel',
            'autoload' => [
                'psr-4' => [
                    'App\\' => 'app/',
                ],
            ],
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
} catch (\Throwable $e) {
    // Gracefully continue
}

return $app;
