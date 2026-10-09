<?php
namespace App\Policies\Qhse;

use App\Domain\QHSE\Models\CauserieSecurite;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class CauserieSecuritePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'responsable_qhse', 'conducteur_travaux', 'chef_chantier');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'responsable_qhse', 'chef_chantier', 'conducteur_travaux');
    }

    public function update(User $user, CauserieSecurite $causerie): bool
    {
        return $user->hasRole('admin', 'responsable_qhse', 'chef_chantier');
    }

    public function delete(User $user, CauserieSecurite $causerie): bool
    {
        return $user->hasRole('admin', 'direction', 'responsable_qhse');
    }
}