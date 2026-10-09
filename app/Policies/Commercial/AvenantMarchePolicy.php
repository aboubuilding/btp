<?php
namespace App\Policies\Commercial;

use App\Domain\Commercial\Models\AvenantMarche;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class AvenantMarchePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'comptable');
    }

    public function view(User $user, AvenantMarche $avenant): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique');
    }

    public function update(User $user, AvenantMarche $avenant): bool
    {
        if ($avenant->est_signe) return false;
        return $user->hasRole('admin', 'direction', 'directeur_technique');
    }

    public function delete(User $user, AvenantMarche $avenant): bool
    {
        if ($avenant->est_signe) return false;
        return $user->hasRole('admin', 'direction');
    }

    public function signer(User $user, AvenantMarche $avenant): bool
    {
        return $user->hasRole('admin', 'direction')
            && !$avenant->est_signe;
    }
}