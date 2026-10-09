<?php
namespace App\Policies\Personnel;

use App\Domain\Personnel\Models\DemandeConge;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class DemandeCongePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return true; // tout utilisateur voit ses propres demandes
    }

    public function view(User $user, DemandeConge $demande): bool
    {
        // Propriétaire, RH, direction ou conducteur
        if ($user->employe?->id === $demande->employee_id) return true;
        return $user->hasRole('admin', 'rh', 'direction', 'directeur_technique');
    }

    public function create(User $user): bool
    {
        return true; // tout employé peut demander un congé
    }

    public function update(User $user, DemandeConge $demande): bool
    {
        if ($demande->statut !== 'demande') return false;
        if ($user->employe?->id === $demande->employee_id) return true;
        return $user->hasRole('admin', 'rh', 'direction');
    }

    public function approuver(User $user, DemandeConge $demande): bool
    {
        return $user->hasRole('admin', 'rh', 'direction', 'directeur_technique')
            && $demande->statut === 'demande';
    }

    public function refuser(User $user, DemandeConge $demande): bool
    {
        return $this->approuver($user, $demande);
    }

    public function delete(User $user, DemandeConge $demande): bool
    {
        if ($demande->statut !== 'demande') return false;
        if ($user->employe?->id === $demande->employee_id) return true;
        return $user->hasRole('admin', 'direction', 'rh');
    }
}