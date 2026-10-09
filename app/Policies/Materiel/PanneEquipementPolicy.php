<?php
namespace App\Policies\Materiel;

use App\Domain\ParcMateriel\Models\PanneEquipement;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class PanneEquipementPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'responsable_materiel', 'chef_chantier', 'conducteur_travaux');
    }

    public function view(User $user, PanneEquipement $panne): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'responsable_materiel', 'chef_chantier', 'conducteur_travaux');
    }

    public function update(User $user, PanneEquipement $panne): bool
    {
        if ($panne->est_cloturee) return false;
        return $user->hasRole('admin', 'responsable_materiel', 'chef_chantier', 'conducteur_travaux');
    }

    public function cloturer(User $user, PanneEquipement $panne): bool
    {
        return $user->hasRole('admin', 'responsable_materiel', 'directeur_technique')
            && !$panne->est_cloturee;
    }

    public function delete(User $user, PanneEquipement $panne): bool
    {
        if ($panne->est_cloturee) return false;
        return $user->hasRole('admin', 'direction');
    }
}