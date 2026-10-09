<?php
namespace App\Policies\Materiel;

use App\Domain\ParcMateriel\Models\ReleveCarburant;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class ReleveCarburantPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'responsable_materiel', 'chef_chantier', 'magasinier');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'responsable_materiel', 'chef_chantier', 'magasinier');
    }

    public function update(User $user, ReleveCarburant $releve): bool
    {
        return $user->hasRole('admin', 'responsable_materiel');
    }

    public function delete(User $user, ReleveCarburant $releve): bool
    {
        return $user->hasRole('admin', 'direction', 'responsable_materiel');
    }
}