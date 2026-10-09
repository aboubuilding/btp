<?php
namespace App\Policies\Personnel;

use App\Domain\Personnel\Models\Departement;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class DepartementPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'rh');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'rh', 'direction');
    }

    public function update(User $user, Departement $departement): bool
    {
        return $user->hasRole('admin', 'rh', 'direction');
    }

    public function delete(User $user, Departement $departement): bool
    {
        if ($departement->employes()->exists()) return false;
        return $user->hasRole('admin', 'direction');
    }
}