<?php
namespace App\Policies\Finances;

use App\Domain\Finances\Models\CompteBancaire;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class CompteBancairePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'comptable');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'comptable', 'direction');
    }

    public function update(User $user, CompteBancaire $compte): bool
    {
        return $user->hasRole('admin', 'comptable', 'direction');
    }

    public function delete(User $user, CompteBancaire $compte): bool
    {
        if ($compte->paiements()->exists()) return false;
        return $user->hasRole('admin', 'direction');
    }
}