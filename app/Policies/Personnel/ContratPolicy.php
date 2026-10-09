<?php
namespace App\Policies\Personnel;

use App\Domain\Personnel\Models\Contrat;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class ContratPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'rh');
    }

    public function view(User $user, Contrat $contrat): bool
    {
        if ($user->employe?->id === $contrat->employee_id) return true;
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'rh', 'direction');
    }

    public function update(User $user, Contrat $contrat): bool
    {
        if ($contrat->statut !== 'en_cours') return false;
        return $user->hasRole('admin', 'rh', 'direction');
    }

    public function resilier(User $user, Contrat $contrat): bool
    {
        return $user->hasRole('admin', 'rh', 'direction')
            && $contrat->statut === 'en_cours';
    }

    public function delete(User $user, Contrat $contrat): bool
    {
        if ($contrat->statut !== 'en_cours') return false;
        return $user->hasRole('admin', 'direction');
    }
}