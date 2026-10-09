<?php
namespace App\Policies\Personnel;

use App\Domain\Personnel\Models\Presence;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class PresencePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'rh', 'conducteur_travaux', 'chef_chantier');
    }

    public function view(User $user, Presence $presence): bool
    {
        if ($user->employe?->id === $presence->employee_id) return true;
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'chef_chantier', 'conducteur_travaux', 'rh');
    }

    public function update(User $user, Presence $presence): bool
    {
        // Une fois validée, seul le RH peut modifier
        if ($presence->est_validee) {
            return $user->hasRole('admin', 'rh');
        }
        return $user->hasRole('admin', 'chef_chantier', 'conducteur_travaux', 'rh');
    }

    public function valider(User $user, Presence $presence): bool
    {
        return $user->hasRole('admin', 'conducteur_travaux', 'directeur_technique', 'rh')
            && !$presence->est_validee;
    }

    public function delete(User $user, Presence $presence): bool
    {
        if ($presence->est_validee) return false;
        return $user->hasRole('admin', 'chef_chantier', 'conducteur_travaux', 'rh');
    }
}