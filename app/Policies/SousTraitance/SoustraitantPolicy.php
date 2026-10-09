<?php
namespace App\Policies\SousTraitance;

use App\Domain\SousTraitance\Models\Soustraitant;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class SoustraitantPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'comptable', 'conducteur_travaux', 'responsable_qhse');
    }

    public function view(User $user, Soustraitant $soustraitant): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'conducteur_travaux');
    }

    public function update(User $user, Soustraitant $soustraitant): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'conducteur_travaux');
    }

    public function delete(User $user, Soustraitant $soustraitant): bool
    {
        if ($soustraitant->contrats()->exists()) return false;
        return $user->hasRole('admin', 'direction');
    }

    public function blacklister(User $user, Soustraitant $soustraitant): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique');
    }
}