<?php
namespace App\Policies\Qhse;

use App\Domain\QHSE\Models\PvReception;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class PvReceptionPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'responsable_qhse', 'conducteur_travaux');
    }

    public function view(User $user, PvReception $pv): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'responsable_qhse', 'conducteur_travaux', 'directeur_technique');
    }

    public function update(User $user, PvReception $pv): bool
    {
        if ($pv->statut === 'signe') return false;
        return $user->hasRole('admin', 'responsable_qhse', 'conducteur_travaux');
    }

    public function signer(User $user, PvReception $pv): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique')
            && $pv->statut !== 'signe';
    }

    public function delete(User $user, PvReception $pv): bool
    {
        if ($pv->statut === 'signe') return false;
        return $user->hasRole('admin', 'direction', 'responsable_qhse');
    }
}