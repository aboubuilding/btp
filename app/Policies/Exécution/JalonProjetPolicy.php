<?php
namespace App\Policies\Execution;

use App\Domain\Execution\Models\JalonProjet;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class JalonProjetPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, JalonProjet $jalon): bool
    {
        return $this->aAccesChantier($user, $jalon->projet);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'directeur_technique', 'conducteur_travaux');
    }

    public function update(User $user, JalonProjet $jalon): bool
    {
        if ($this->estDirection($user)) return true;
        return $this->estResponsableChantier($user, $jalon->projet);
    }

    public function delete(User $user, JalonProjet $jalon): bool
    {
        return $user->hasRole('admin', 'directeur_technique', 'conducteur_travaux');
    }

    /** Marquer un jalon atteint. */
    public function marquerAtteint(User $user, JalonProjet $jalon): bool
    {
        return $this->update($user, $jalon) && !$jalon->est_atteint;
    }
}