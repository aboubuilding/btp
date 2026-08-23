<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Afficher le formulaire de connexion
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Traiter la tentative de connexion
     */
    public function login(Request $request): JsonResponse
    {
        // Validation des données - Utiliser 'mot_de_passe' au lieu de 'password'
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:255'],
            'mot_de_passe' => ['required', 'string', 'min:6'],
        ], [
            // Messages d'erreur personnalisés en français
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'Veuillez saisir une adresse email valide.',
            'email.max' => 'L\'adresse email ne doit pas dépasser 255 caractères.',
            'mot_de_passe.required' => 'Le mot de passe est obligatoire.',
            'mot_de_passe.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Veuillez corriger les erreurs du formulaire.'
            ], 422);
        }

        // Authentification - Utiliser 'mot_de_passe' comme clé
        $result = $this->authService->authenticate(
            $request->input('email'),
            $request->input('mot_de_passe')
        );

        if ($result['success']) {
            return response()->json($result);
        }

        return response()->json($result, 401);
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        $this->authService->logout();
        return redirect()->route('login');
    }

    /**
     * Vérifier la session
     */
    public function checkSession(): JsonResponse
    {
        return response()->json([
            'authenticated' => $this->authService->check(),
            'user' => $this->authService->getUser()
        ]);
    }
}
