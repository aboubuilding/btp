<?php
namespace App\Policies\Materiel;

use App\Domain\ParcMateriel\Models\CategorieEquipement;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class CategorieEquipementPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'responsable_materiel', 'conducteur_travaux');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'responsable_materiel', 'directeur_technique');
    }

    public function update(User $user, CategorieEquipement $categorie): bool
    {
        return $user->hasRole('admin', 'responsable_materiel', 'directeur_technique');
    }

    public function delete(User $user, CategorieEquipement $categorie): bool
    {
        if ($categorie->equipements()->exists()) return false;
        return $user->hasRole('admin', 'direction');
    }
}