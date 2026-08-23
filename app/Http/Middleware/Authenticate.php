<?php

namespace App\Http\Middleware;

use App\Services\AuthService;
use Closure;
use Illuminate\Http\Request;

class Authenticate
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si l'utilisateur est authentifié
        if (!$this->authService->check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non authentifié. Veuillez vous connecter.',
                    'code' => 401
                ], 401);
            }

            return redirect()->route('login');
        }

        // Vérifier si le compte est actif
        $user = $this->authService->getUser();
        if ($user && !$user->isActive()) {
            $this->authService->logout();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Votre compte a été désactivé.',
                    'code' => 403
                ], 403);
            }

            return redirect()->route('login')
                ->withErrors(['login' => 'Votre compte a été désactivé.']);
        }

        return $next($request);
    }
}
