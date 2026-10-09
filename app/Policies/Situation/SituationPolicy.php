<?php
namespace App\Policies\Situation;

use App\Domain\Execution\Models\Situation;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class SituationPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'comptable', 'metreur', 'conducteur_travaux');
    }

    public function view(User $user, Situation $situation): bool
    {
        return $this->aAccesChantier($user, $situation->projet);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'directeur_technique', 'metreur', 'conducteur_travaux');
    }

    public function update(User $user, Situation $situation): bool
    {
        if (!$situation->est_modifiable) return false;
        return $user->hasRole('admin', 'directeur_technique', 'metreur', 'conducteur_travaux');
    }

    public function delete(User $user, Situation $situation): bool
    {
        if ($situation->statut !== 'brouillon') return false;
        return $user->hasRole('admin', 'directeur_technique', 'direction');
    }

    /** Valider une situation (DT). */
    public function valider(User $user, Situation $situation): bool
    {
        return $user->hasRole('admin', 'directeur_technique', 'direction')
            && $situation->statut === 'brouillon';
    }

    /** Transmettre la situation au maître d'œuvre. */
    public function transmettre(User $user, Situation $situation): bool
    {
        return $user->hasRole('admin', 'directeur_technique', 'direction')
            && $situation->statut === 'validee';
    }

    /** Approuver la situation (retour client). */
    public function approuver(User $user, Situation $situation): bool
    {
        return $user->hasRole('admin', 'directeur_technique', 'direction')
            && $situation->statut === 'transmise';
    }

    /** Facturer une situation approuvée. */
    public function facturer(User $user, Situation $situation): bool
    {
        return $user->hasRole('admin', 'direction', 'comptable')
            && $situation->statut === 'approuvee';
    }

    /** Générer le PDF. */
    public function pdf(User $user, Situation $situation): bool
    {
        return $this->view($user, $situation);
    }
}