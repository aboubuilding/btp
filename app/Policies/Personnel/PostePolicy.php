<?php
namespace App\Policies\Personnel;

use App\Domain\Personnel\Models\Poste;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class PostePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'rh');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'rh', 'direction');
    }

    public function update(User $user, Poste $poste): bool
    {
        return $user->hasRole('admin', 'rh', 'direction');
    }

    public function delete(User $user, Poste $poste): bool
    {
        if ($poste->employes()->exists()) return false;
        return $user->hasRole('admin', 'direction');
    }
}