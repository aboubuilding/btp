<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserService
{
    protected UserRepositoryInterface $userRepository;
    protected RoleRepositoryInterface $roleRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        RoleRepositoryInterface $roleRepository
    ) {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    public function getAllUsers(): array
    {
        try {
            return $this->userRepository->getUsersWithRole();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des utilisateurs: ' . $e->getMessage());
            return [];
        }
    }

    public function getUser(int $id): ?User
    {
        try {
            return $this->userRepository->withSupprime()->find($id);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération de l\'utilisateur: ' . $e->getMessage());
            return null;
        }
    }

    public function getRoles(): array
    {
        try {
            return $this->roleRepository->getActiveRoles();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des rôles: ' . $e->getMessage());
            return [];
        }
    }

    public function createUser(array $data): ?User
    {
        try {
            $data['mot_de_passe'] = Hash::make($data['mot_de_passe']);
            $data['etat'] = 1;
            $data['est_actif'] = true;

            return $this->userRepository->create($data);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de l\'utilisateur: ' . $e->getMessage());
            return null;
        }
    }

    public function updateUser(int $id, array $data): bool
    {
        try {
            if (isset($data['mot_de_passe']) && !empty($data['mot_de_passe'])) {
                $data['mot_de_passe'] = Hash::make($data['mot_de_passe']);
            } else {
                unset($data['mot_de_passe']);
            }

            return $this->userRepository->update($id, $data);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de l\'utilisateur: ' . $e->getMessage());
            return false;
        }
    }

    public function toggleActive(int $id): bool
    {
        try {
            return $this->userRepository->toggleActive($id);
        } catch (\Exception $e) {
            Log::error('Erreur lors du basculement de l\'utilisateur: ' . $e->getMessage());
            return false;
        }
    }

    public function resetPassword(int $id): bool
    {
        try {
            $password = Str::random(12);
            return $this->userRepository->resetPassword($id, $password);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la réinitialisation du mot de passe: ' . $e->getMessage());
            return false;
        }
    }

    public function assignRole(int $userId, int $roleId): bool
    {
        try {
            return $this->userRepository->assignRole($userId, $roleId);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'assignation du rôle: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteUser(int $id): bool
    {
        try {
            return $this->userRepository->delete($id);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de l\'utilisateur: ' . $e->getMessage());
            return false;
        }
    }

    public function restoreUser(int $id): bool
    {
        try {
            return $this->userRepository->restore($id);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la restauration de l\'utilisateur: ' . $e->getMessage());
            return false;
        }
    }

    public function searchUsers(string $keyword): array
    {
        try {
            return $this->userRepository->search($keyword);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la recherche: ' . $e->getMessage());
            return [];
        }
    }

    public function getPasswordReset(): string
    {
        return Str::random(12);
    }
}
