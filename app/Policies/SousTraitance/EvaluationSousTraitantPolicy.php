<?php
namespace App\Policies\SousTraitance;

use App\Domain\SousTraitance\Models\EvaluationSousTraitant;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class EvaluationSousTraitantPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'conducteur_travaux', 'responsable_qhse');
    }

    public function view(User $user, EvaluationSousTraitant $evaluation): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'conducteur_travaux', 'responsable_qhse');
    }

    public function update(User $user, EvaluationSousTraitant $evaluation): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'conducteur_travaux', 'responsable_qhse');
    }

    public function delete(User $user, EvaluationSousTraitant $evaluation): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique');
    }
}