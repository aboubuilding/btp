<?php
namespace App\Policies\Materiel;

use App\Domain\ParcMateriel\Models\Equipement;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class EquipementPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'responsable_materiel', 'magasinier', 'conducteur_travaux', 'chef_chantier', 'responsable_qhse');
    }

    public function view(User $user, Equipement $equipement): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'responsable_materiel', 'direction');
    }

    public function update(User $user, Equipement $equipement): bool
    {
        return $user->hasRole('admin', 'responsable_materiel', 'direction');
    }

    public function delete(User $user, Equipement $equipement): bool
    {
        if ($equipement->affectations()->exists()) return false;
        return $user->hasRole('admin', 'direction');
    }

    public function affecter(User $user, Equipement $equipement): bool
    {
        return $user->hasRole('admin', 'responsable_materiel', 'directeur_technique')
            && $equipement->statut === 'disponible';
    }

    public function fermerAffectation(User $user, Equipement $equipement): bool
    {
        return $user->hasRole('admin', 'responsable_materiel', 'directeur_technique');
    }

    public function changerStatut(User $user, Equipement $equipement): bool
    {
        return $user->hasRole('admin', 'responsable_materiel');
    }
}