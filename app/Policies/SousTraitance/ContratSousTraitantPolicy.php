<?php
namespace App\Policies\SousTraitance;

use App\Domain\SousTraitance\Models\ContratSousTraitant;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class ContratSousTraitantPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'comptable', 'conducteur_travaux');
    }

    public function view(User $user, ContratSousTraitant $contrat): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'conducteur_travaux');
    }

    public function update(User $user, ContratSousTraitant $contrat): bool
    {
        if ($contrat->statut === 'resilie') return false;
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'conducteur_travaux');
    }

    public function resilier(User $user, ContratSousTraitant $contrat): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique')
            && $contrat->statut === 'en_cours';
    }

    public function delete(User $user, ContratSousTraitant $contrat): bool
    {
        if ($contrat->factures()->exists()) return false;
        return $user->hasRole('admin', 'direction');
    }
}