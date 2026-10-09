<?php
namespace App\Policies\Situation;

use App\Domain\Execution\Models\LigneAttachement;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class LigneAttachementPolicy extends BasePolicy
{
    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'directeur_technique', 'metreur', 'conducteur_travaux');
    }

    public function update(User $user, LigneAttachement $ligne): bool
    {
        if (!$ligne->attachement->est_modifiable) return false;
        return $user->hasRole('admin', 'directeur_technique', 'metreur', 'conducteur_travaux');
    }

    public function delete(User $user, LigneAttachement $ligne): bool
    {
        if (!$ligne->attachement->est_modifiable) return false;
        return $user->hasRole('admin', 'directeur_technique', 'metreur', 'conducteur_travaux');
    }
}