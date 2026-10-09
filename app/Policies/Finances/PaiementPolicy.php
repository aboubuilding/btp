<?php
namespace App\Policies\Finances;

use App\Domain\Finances\Models\Paiement;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class PaiementPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'comptable');
    }

    public function view(User $user, Paiement $paiement): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'comptable', 'chef_chantier');
    }

    public function update(User $user, Paiement $paiement): bool
    {
        return $user->hasRole('admin', 'comptable', 'direction');
    }

    public function delete(User $user, Paiement $paiement): bool
    {
        return $user->hasRole('admin', 'direction');
    }

    public function lettrer(User $user, Paiement $paiement): bool
    {
        return $user->hasRole('admin', 'comptable');
    }
}