<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class AuthService
{
    /**
     * Authenticate a user by email
     *
     * @param string $email
     * @param string $password
     * @return array
     */
    public function authenticate(string $email, string $password): array
    {
        // Rechercher l'utilisateur par email
        $user = User::where('email', $email)->first();

        // 1. Vérifier si l'utilisateur existe
        if (!$user) {
            return [
                'success' => false,
                'code' => 'USER_NOT_FOUND',
                'message' => 'Email ou mot de passe incorrect.'
            ];
        }

        // 2. Vérifier le mot de passe - Utiliser 'mot_de_passe'
        if (!Hash::check($password, $user->mot_de_passe)) {
            return [
                'success' => false,
                'code' => 'INVALID_PASSWORD',
                'message' => 'Email ou mot de passe incorrect.'
            ];
        }

        // 3. Vérifier si le compte est actif
        if (!$user->isActive()) {
            return [
                'success' => false,
                'code' => 'ACCOUNT_INACTIVE',
                'message' => 'Votre compte est désactivé. Veuillez contacter l\'administrateur.'
            ];
        }

        // 4. Authentification réussie
        $user->derniere_connexion_le = Carbon::now();
        $user->save();

        // Démarrer la session
        Session::put('user_id', $user->id);
        Session::put('user_nom', $user->nom);
        Session::put('user_email', $user->email);
        Session::put('user_role', $user->role ? $user->role->slug : null);

        return [
            'success' => true,
            'code' => 'SUCCESS',
            'message' => 'Connexion réussie !',
            'user' => $user,
            'redirect' => route('dashboard')
        ];
    }

    /**
     * Logout user
     */
    public function logout(): void
    {
        Session::flush();
        session()->regenerate();
    }

    /**
     * Check if user is authenticated
     */
    public function check(): bool
    {
        return Session::has('user_id');
    }

    /**
     * Get authenticated user
     */
    public function getUser(): ?User
    {
        if ($this->check()) {
            return User::find(Session::get('user_id'));
        }
        return null;
    }

    /**
     * Get authenticated user ID
     */
    public function getUserId(): ?int
    {
        return Session::get('user_id');
    }

    /**
     * Get authenticated user email
     */
    public function getUserEmail(): ?string
    {
        return Session::get('user_email');
    }

    /**
     * Get authenticated user role
     */
    public function getUserRole(): ?string
    {
        return Session::get('user_role');
    }

    /**
     * Check if user has specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->getUserRole() === $role;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }
}
