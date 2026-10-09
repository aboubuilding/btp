<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Domain\Socle\Repositories\UserRepositoryInterface;
use App\Domain\Socle\Events\UtilisateurConnecte;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\{Auth, Hash, RateLimiter};

class LoginController extends Controller
{
    public function __construct(private UserRepositoryInterface $users) {}

    public function showLoginForm()
    {
        if (Auth::check()) return redirect()->route('dashboard');
        return view('auth.login');
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'        => ['required', 'email'],
            'mot_de_passe' => ['required', 'string', 'min:6'],
        ]);

        $key = 'login:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'success' => false,
                'code'    => 'TOO_MANY_ATTEMPTS',
                'message' => "Trop de tentatives. Réessayez dans {$seconds}s.",
            ], 429);
        }

        $user = $this->users->findByEmail($request->email);

        if (!$user) {
            RateLimiter::hit($key, 60);
            return response()->json([
                'success' => false,
                'code'    => 'USER_NOT_FOUND',
                'message' => 'Aucun compte ne correspond à cet email.',
            ], 401);
        }

        if (!$user->est_actif) {
            return response()->json([
                'success' => false,
                'code'    => 'ACCOUNT_INACTIVE',
                'message' => 'Votre compte a été désactivé.',
            ], 403);
        }

        if (!Hash::check($request->mot_de_passe, $user->mot_de_passe)) {
            RateLimiter::hit($key, 60);
            return response()->json([
                'success' => false,
                'code'    => 'INVALID_PASSWORD',
                'message' => 'Mot de passe incorrect.',
            ], 401);
        }

        Auth::login($user, $request->boolean('remember'));
        RateLimiter::clear($key);

        event(new UtilisateurConnecte($user, $request->ip(), $request->userAgent()));

        return response()->json([
            'success'  => true,
            'message'  => 'Connexion réussie.',
            'redirect' => route('dashboard'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}