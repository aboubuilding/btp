<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $users = $this->userService->getAllUsers();
        $roles = $this->userService->getRoles();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function store(UserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());

        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'Utilisateur créé avec succès.',
                'user' => $user
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la création de l\'utilisateur.'
        ], 500);
    }

    public function update(UserRequest $request, int $id)
    {
        $updated = $this->userService->updateUser($id, $request->validated());

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Utilisateur mis à jour avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la mise à jour de l\'utilisateur.'
        ], 500);
    }

    public function toggleActive(int $id)
    {
        $toggled = $this->userService->toggleActive($id);

        if ($toggled) {
            $user = $this->userService->getUser($id);
            $status = $user && $user->est_actif ? 'activé' : 'désactivé';
            return response()->json([
                'success' => true,
                'message' => "Utilisateur {$status} avec succès.",
                'status' => $user ? $user->est_actif : null
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de l\'opération.'
        ], 500);
    }

    public function resetPassword(int $id)
    {
        $reset = $this->userService->resetPassword($id);

        if ($reset) {
            return response()->json([
                'success' => true,
                'message' => 'Mot de passe réinitialisé avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la réinitialisation du mot de passe.'
        ], 500);
    }

    public function assignRole(Request $request, int $id)
    {
        $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        $assigned = $this->userService->assignRole($id, $request->input('role_id'));

        if ($assigned) {
            return response()->json([
                'success' => true,
                'message' => 'Rôle assigné avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de l\'assignation du rôle.'
        ], 500);
    }

    public function destroy(int $id)
    {
        $deleted = $this->userService->deleteUser($id);

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Utilisateur supprimé avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la suppression de l\'utilisateur.'
        ], 500);
    }

    public function restore(int $id)
    {
        $restored = $this->userService->restoreUser($id);

        if ($restored) {
            return response()->json([
                'success' => true,
                'message' => 'Utilisateur restauré avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la restauration de l\'utilisateur.'
        ], 500);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('q', '');
        $users = $this->userService->searchUsers($keyword);

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }
}
