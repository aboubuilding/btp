<?php
namespace App\Policies\Stock;

use App\Domain\Approvisionnement\Models\MouvementStock;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class MouvementStockPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'responsable_achat', 'magasinier', 'conducteur_travaux', 'chef_chantier');
    }

    public function view(User $user, MouvementStock $mouvement): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'magasinier', 'responsable_achat', 'chef_chantier');
    }

    public function delete(User $user, MouvementStock $mouvement): bool
    {
        return $user->hasRole('admin', 'direction', 'responsable_achat');
    }
}