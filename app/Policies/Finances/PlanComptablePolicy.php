<?php
namespace App\Policies\Finances;

use App\Domain\Finances\Models\PlanComptable;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class PlanComptablePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'comptable');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'comptable');
    }

    public function update(User $user, PlanComptable $compte): bool
    {
        return $user->hasRole('admin', 'comptable');
    }

    public function delete(User $user, PlanComptable $compte): bool
    {
        if ($compte->lignesEcritures()->exists()) return false;
        return $user->hasRole('admin', 'direction');
    }
}