<?php
namespace App\Policies\Situation;

use App\Domain\Execution\Models\Attachement;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class AttachementPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'comptable', 'metreur', 'conducteur_travaux', 'chef_chantier');
    }

    public function view(User $user, Attachement $attachement): bool
    {
        return $this->aAccesChantier($user, $attachement->projet);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'directeur_technique', 'metreur', 'conducteur_travaux');
    }

    public function update(User $user, Attachement $attachement): bool
    {
        if (!$attachement->est_modifiable) return false;
        return $user->hasRole('admin', 'directeur_technique', 'metreur', 'conducteur_travaux');
    }

    public function delete(User $user, Attachement $attachement): bool
    {
        if ($attachement->statut === 'valide') return false;
        if ($attachement->situation()->exists()) return false;
        return $user->hasRole('admin', 'directeur_technique', 'conducteur_travaux');
    }

    public function valider(User $user, Attachement $attachement): bool
    {
        return $user->hasRole('admin', 'directeur_technique', 'conducteur_travaux')
            && $attachement->statut !== 'valide';
    }
}