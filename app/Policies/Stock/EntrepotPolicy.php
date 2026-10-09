<?php
namespace App\Policies\Stock;

use App\Domain\Approvisionnement\Models\Entrepot;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class EntrepotPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'responsable_achat', 'magasinier', 'conducteur_travaux', 'chef_chantier');
    }

    public function view(User $user, Entrepot $entrepot): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'responsable_achat', 'directeur_technique');
    }

    public function update(User $user, Entrepot $entrepot): bool
    {
        return $user->hasRole('admin', 'responsable_achat', 'directeur_technique');
    }

    public function delete(User $user, Entrepot $entrepot): bool
    {
        if ($entrepot->niveaux()->where('quantite', '>', 0)->exists()) return false;
        return $user->hasRole('admin', 'direction');
    }
}