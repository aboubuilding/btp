<?php
namespace App\Policies\Execution;

use App\Domain\Execution\Models\Tache;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class TachePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Tache $tache): bool
    {
        return $this->aAccesChantier($user, $tache->projet);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'directeur_technique', 'conducteur_travaux');
    }

    public function update(User $user, Tache $tache): bool
    {
        if ($this->estDirection($user)) return true;
        if ($this->estResponsableChantier($user, $tache->projet)) return true;
        // L'assigné peut mettre à jour son avancement
        return $tache->assigne_a === $user->employe?->id;
    }

    public function delete(User $user, Tache $tache): bool
    {
        return $user->hasRole('admin', 'directeur_technique', 'conducteur_travaux');
    }

    public function mettreAJourAvancement(User $user, Tache $tache): bool
    {
        if ($this->estDirection($user)) return true;
        if ($this->estResponsableChantier($user, $tache->projet)) return true;
        return $tache->assigne_a === $user->employe?->id;
    }
}