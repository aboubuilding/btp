<?php
namespace App\Policies\Execution;

use App\Domain\Execution\Models\AvancementProjet;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class AvancementProjetPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, AvancementProjet $avancement): bool
    {
        return $this->aAccesChantier($user, $avancement->projet);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'directeur_technique', 'conducteur_travaux', 'chef_chantier');
    }

    public function update(User $user, AvancementProjet $avancement): bool
    {
        if ($avancement->est_valide) return false;
        if ($this->estDirection($user)) return true;
        return $this->estResponsableChantier($user, $avancement->projet);
    }

    public function valider(User $user, AvancementProjet $avancement): bool
    {
        return $user->hasRole('admin', 'directeur_technique', 'conducteur_travaux')
            && !$avancement->est_valide;
    }

    public function delete(User $user, AvancementProjet $avancement): bool
    {
        if ($avancement->est_valide) return false;
        return $user->hasRole('admin', 'directeur_technique', 'conducteur_travaux');
    }
}