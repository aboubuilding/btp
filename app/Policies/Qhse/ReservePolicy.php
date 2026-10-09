<?php
namespace App\Policies\Qhse;

use App\Domain\QHSE\Models\Reserve;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class ReservePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'responsable_qhse', 'conducteur_travaux', 'chef_chantier');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'responsable_qhse', 'conducteur_travaux', 'directeur_technique');
    }

    public function update(User $user, Reserve $reserve): bool
    {
        if ($reserve->statut === 'levee') return false;
        return $user->hasRole('admin', 'responsable_qhse', 'conducteur_travaux');
    }

    public function lever(User $user, Reserve $reserve): bool
    {
        return $user->hasRole('admin', 'responsable_qhse', 'conducteur_travaux', 'directeur_technique')
            && $reserve->statut === 'ouverte';
    }

    public function delete(User $user, Reserve $reserve): bool
    {
        if ($reserve->statut === 'levee') return false;
        return $user->hasRole('admin', 'direction', 'responsable_qhse');
    }
}