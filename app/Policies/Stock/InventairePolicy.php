<?php
namespace App\Policies\Stock;

use App\Domain\Approvisionnement\Models\Inventaire;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class InventairePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'responsable_achat', 'magasinier');
    }

    public function view(User $user, Inventaire $inventaire): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'responsable_achat', 'magasinier');
    }

    public function update(User $user, Inventaire $inventaire): bool
    {
        if ($inventaire->statut === 'valide') return false;
        return $user->hasRole('admin', 'responsable_achat', 'magasinier');
    }

    public function valider(User $user, Inventaire $inventaire): bool
    {
        return $user->hasRole('admin', 'responsable_achat', 'directeur_technique')
            && $inventaire->statut === 'brouillon';
    }

    public function delete(User $user, Inventaire $inventaire): bool
    {
        if ($inventaire->statut === 'valide') return false;
        return $user->hasRole('admin', 'direction');
    }
}