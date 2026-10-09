<?php
namespace App\Policies\Personnel;

use App\Domain\Personnel\Models\Employe;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class EmployePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'rh', 'conducteur_travaux', 'responsable_qhse', 'comptable');
    }

    public function view(User $user, Employe $employe): bool
    {
        // L'employé peut voir sa propre fiche
        if ($user->employe?->id === $employe->id) return true;
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'rh', 'direction');
    }

    public function update(User $user, Employe $employe): bool
    {
        return $user->hasRole('admin', 'rh', 'direction');
    }

    public function delete(User $user, Employe $employe): bool
    {
        if ($employe->contrats()->exists()) return false;
        if ($employe->presences()->exists()) return false;
        return $user->hasRole('admin', 'direction');
    }

    public function consulterDocuments(User $user, Employe $employe): bool
    {
        if ($user->employe?->id === $employe->id) return true;
        return $user->hasRole('admin', 'rh', 'direction', 'responsable_qhse');
    }
}