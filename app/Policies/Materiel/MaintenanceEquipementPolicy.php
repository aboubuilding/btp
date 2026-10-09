<?php
namespace App\Policies\Materiel;

use App\Domain\ParcMateriel\Models\MaintenanceEquipement;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class MaintenanceEquipementPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'responsable_materiel', 'conducteur_travaux');
    }

    public function view(User $user, MaintenanceEquipement $maintenance): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'responsable_materiel');
    }

    public function update(User $user, MaintenanceEquipement $maintenance): bool
    {
        return $user->hasRole('admin', 'responsable_materiel');
    }

    public function delete(User $user, MaintenanceEquipement $maintenance): bool
    {
        return $user->hasRole('admin', 'direction', 'responsable_materiel');
    }
}