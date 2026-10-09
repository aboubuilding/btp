<?php
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web:      __DIR__ . '/../routes/web.php',
        api:      __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health:   '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Middleware global
        $middleware->append(\App\Http\Middleware\VerifierActivation::class);

        // Alias
        $middleware->alias([
            'role'             => \App\Http\Middleware\VerifierRole::class,
            'verifie'          => \App\Http\Middleware\VerifierActivation::class,
            'filter.chantier'  => \App\Http\Middleware\FiltrerParChantier::class,
            'verified.optional'=> \App\Http\Middleware\OptionalEmailVerification::class,
            'admin'            => \App\Http\Middleware\VerifierRole::class . ':admin',
        ]);

        // Groupes
        $middleware->group('admin', [
            'auth',
            \App\Http\Middleware\VerifierRole::class . ':admin',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Gestion personnalisée des exceptions
        $exceptions->render(function (\App\Domain\Socle\Exceptions\RegleGestionException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }
            return back()->with('error', $e->getMessage())->withInput();
        });
    })
    ->create();