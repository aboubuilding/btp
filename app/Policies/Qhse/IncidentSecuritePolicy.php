<?php
namespace App\Policies\Qhse;

use App\Domain\QHSE\Models\IncidentSecurite;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class IncidentSecuritePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'rh', 'responsable_qhse', 'conducteur_travaux', 'chef_chantier');
    }

    public function view(User $user, IncidentSecurite $incident): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'chef_chantier', 'conducteur_travaux', 'responsable_qhse');
    }

    public function update(User $user, IncidentSecurite $incident): bool
    {
        if ($incident->statut === 'clos') return false;
        return $user->hasRole('admin', 'responsable_qhse', 'directeur_technique', 'direction');
    }

    public function cloturer(User $user, IncidentSecurite $incident): bool
    {
        return $user->hasRole('admin', 'responsable_qhse', 'direction', 'directeur_technique')
            && $incident->statut !== 'clos';
    }

    public function delete(User $user, IncidentSecurite $incident): bool
    {
        if ($incident->statut === 'clos') return false;
        return $user->hasRole('admin', 'direction', 'responsable_qhse');
    }
}