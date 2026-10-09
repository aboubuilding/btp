<?php
namespace App\Policies\Approvisionnement;

use App\Domain\Approvisionnement\Models\Fournisseur;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class FournisseurPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'comptable', 'responsable_achat', 'magasinier', 'responsable_materiel');
    }

    public function view(User $user, Fournisseur $fournisseur): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'responsable_achat', 'direction');
    }

    public function update(User $user, Fournisseur $fournisseur): bool
    {
        return $user->hasRole('admin', 'responsable_achat', 'direction');
    }

    public function delete(User $user, Fournisseur $fournisseur): bool
    {
        if ($fournisseur->bonsCommande()->exists()) return false;
        return $user->hasRole('admin', 'direction');
    }
}