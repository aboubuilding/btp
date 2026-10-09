<?php
namespace App\Policies\Execution;

use App\Domain\Execution\Models\LigneBudget;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class LigneBudgetPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'conducteur_travaux', 'comptable');
    }

    public function view(User $user, LigneBudget $ligne): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'directeur_technique', 'direction');
    }

    public function update(User $user, LigneBudget $ligne): bool
    {
        return $user->hasRole('admin', 'directeur_technique', 'direction', 'comptable');
    }

    public function delete(User $user, LigneBudget $ligne): bool
    {
        if ($ligne->depenses()->exists()) return false;
        return $user->hasRole('admin', 'direction');
    }
}