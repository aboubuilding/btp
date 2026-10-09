<?php
namespace App\Policies\Execution;

use App\Domain\Execution\Models\PhaseProjet;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class PhaseProjetPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'conducteur_travaux', 'chef_chantier', 'metreur');
    }

    public function view(User $user, PhaseProjet $phase): bool
    {
        return $this->aAccesChantier($user, $phase->projet);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'directeur_technique', 'conducteur_travaux');
    }

    public function update(User $user, PhaseProjet $phase): bool
    {
        if ($this->estDirection($user)) return true;
        return $this->estResponsableChantier($user, $phase->projet);
    }

    public function delete(User $user, PhaseProjet $phase): bool
    {
        if ($phase->taches()->exists()) return false;
        return $user->hasRole('admin', 'directeur_technique', 'conducteur_travaux');
    }
}