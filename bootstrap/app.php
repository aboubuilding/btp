<?php

use App\Http\Middleware\Authenticate;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    // ============================================
    // CONFIGURATION DES ROUTES
    // ============================================
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    // ============================================
    // CONFIGURATION DES MIDDLEWARES
    // ============================================
    ->withMiddleware(function (Middleware $middleware): void {

        // ---- 1. ALIAS DES MIDDLEWARES ----
        $middleware->alias([
            'auth' => Authenticate::class,
        ]);

        // ---- 2. REDIRECTIONS ----
        $middleware->redirectGuestsTo(fn () => route('login'));

        // ---- 3. MIDDLEWARES DU GROUPE WEB ----
        // Laravel 12 gère automatiquement les middlewares web par défaut
        // On ajoute seulement nos middlewares personnalisés
        $middleware->web(append: [
            // Ajouter ici vos middlewares personnalisés pour le groupe web
        ]);

        // ---- 4. MIDDLEWARES DU GROUPE API ----
        $middleware->api(prepend: [
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
        ]);

        // ---- 5. PRIORITE DES MIDDLEWARES ----
        $middleware->priority([
            \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\Authenticate::class,
        ]);
    })

    // ============================================
    // CONFIGURATION DES EXCEPTIONS
    // ============================================
    ->withExceptions(function (Exceptions $exceptions): void {

        // ---- 1. GESTION DES ERREURS API ----
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->expectsJson()) {
                $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;

                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'code' => $status,
                ], $status);
            }

            // Personnaliser l'erreur 404
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                return response()->view('errors.404', [], 404);
            }

            // Personnaliser l'erreur 403
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException) {
                return response()->view('errors.403', [], 403);
            }

            return null;
        });

        // ---- 2. EXCEPTIONS A NE PAS RAPPORTER ----
        $exceptions->dontReport([
            \Illuminate\Auth\AuthenticationException::class,
            \Illuminate\Auth\Access\AuthorizationException::class,
            \Symfony\Component\HttpKernel\Exception\HttpException::class,
            \Illuminate\Database\Eloquent\ModelNotFoundException::class,
            \Illuminate\Session\TokenMismatchException::class,
            \Illuminate\Validation\ValidationException::class,
        ]);

        // ---- 3. CHAMPS A NE PAS FLASHER ----
        $exceptions->dontFlash([
            'current_password',
            'password',
            'password_confirmation',
        ]);
    })

    ->create();
