<?php
namespace App\Policies\Materiel;

use App\Domain\ParcMateriel\Models\AffectationEquipement;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class AffectationEquipementPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'responsable_materiel', 'conducteur_travaux', 'chef_chantier');
    }

    public function view(User $user, AffectationEquipement $affectation): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'responsable_materiel', 'directeur_technique');
    }

    public function update(User $user, AffectationEquipement $affectation): bool
    {
        return $user->hasRole('admin', 'responsable_materiel', 'directeur_technique');
    }

    public function fermer(User $user, AffectationEquipement $affectation): bool
    {
        return $user->hasRole('admin', 'responsable_materiel', 'directeur_technique')
            && $affectation->est_ouverte;
    }
}