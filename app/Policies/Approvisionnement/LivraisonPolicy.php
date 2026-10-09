<?php
namespace App\Policies\Approvisionnement;

use App\Domain\Approvisionnement\Models\Livraison;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class LivraisonPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'comptable', 'responsable_achat', 'magasinier');
    }

    public function view(User $user, Livraison $livraison): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'magasinier', 'responsable_achat');
    }

    public function update(User $user, Livraison $livraison): bool
    {
        return $user->hasRole('admin', 'magasinier', 'responsable_achat');
    }

    public function delete(User $user, Livraison $livraison): bool
    {
        if ($livraison->statut === 'complete') return false;
        return $user->hasRole('admin', 'direction', 'responsable_achat');
    }

    public function refuser(User $user, Livraison $livraison): bool
    {
        return $user->hasRole('admin', 'magasinier', 'responsable_achat')
            && $livraison->statut !== 'refusee';
    }
}