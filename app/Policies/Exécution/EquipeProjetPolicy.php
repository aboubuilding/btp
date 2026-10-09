<?php
namespace App\Policies\Execution;

use App\Domain\Execution\Models\EquipeProjet;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class EquipeProjetPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, EquipeProjet $equipe): bool
    {
        return $this->aAccesChantier($user, $equipe->projet);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'directeur_technique', 'conducteur_travaux');
    }

    public function update(User $user, EquipeProjet $equipe): bool
    {
        if ($this->estDirection($user)) return true;
        return $this->estResponsableChantier($user, $equipe->projet);
    }

    public function delete(User $user, EquipeProjet $equipe): bool
    {
        if ($this->estDirection($user)) return true;
        return $this->estResponsableChantier($user, $equipe->projet);
    }
}