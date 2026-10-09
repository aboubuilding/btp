<?php
namespace App\Policies\Finances;

use App\Domain\Finances\Models\Caisse;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class CaissePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'comptable', 'chef_chantier');
    }

    public function view(User $user, Caisse $caisse): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'comptable', 'direction');
    }

    public function update(User $user, Caisse $caisse): bool
    {
        return $user->hasRole('admin', 'comptable', 'direction');
    }

    public function alimenter(User $user, Caisse $caisse): bool
    {
        return $user->hasRole('admin', 'comptable', 'direction');
    }

    public function delete(User $user, Caisse $caisse): bool
    {
        if ($caisse->paiements()->exists()) return false;
        if ($caisse->depenses()->exists()) return false;
        return $user->hasRole('admin', 'direction');
    }
}