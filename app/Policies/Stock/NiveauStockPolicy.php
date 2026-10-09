<?php
namespace App\Policies\Stock;

use App\Domain\Approvisionnement\Models\NiveauStock;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class NiveauStockPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'responsable_achat', 'magasinier', 'conducteur_travaux', 'chef_chantier');
    }

    public function view(User $user, NiveauStock $niveau): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, NiveauStock $niveau): bool
    {
        return $user->hasRole('admin', 'responsable_achat');
    }

    public function ajuster(User $user, NiveauStock $niveau): bool
    {
        return $user->hasRole('admin', 'responsable_achat', 'magasinier');
    }
}