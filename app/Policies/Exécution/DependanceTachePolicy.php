<?php
namespace App\Policies\Execution;

use App\Domain\Execution\Models\DependanceTache;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class DependanceTachePolicy extends BasePolicy
{
    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'directeur_technique', 'conducteur_travaux');
    }

    public function update(User $user, DependanceTache $dependance): bool
    {
        return $user->hasRole('admin', 'directeur_technique', 'conducteur_travaux');
    }

    public function delete(User $user, DependanceTache $dependance): bool
    {
        return $user->hasRole('admin', 'directeur_technique', 'conducteur_travaux');
    }
}