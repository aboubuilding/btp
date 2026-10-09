<?php
namespace App\Policies\Stock;

use App\Domain\Approvisionnement\Models\CategorieMateriau;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class CategorieMateriauPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'responsable_achat', 'magasinier', 'metreur');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'responsable_achat', 'directeur_technique');
    }

    public function update(User $user, CategorieMateriau $categorie): bool
    {
        return $user->hasRole('admin', 'responsable_achat', 'directeur_technique');
    }

    public function delete(User $user, CategorieMateriau $categorie): bool
    {
        if ($categorie->materiaux()->exists()) return false;
        return $user->hasRole('admin', 'direction');
    }
}