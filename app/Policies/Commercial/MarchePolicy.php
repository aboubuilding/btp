<?php
namespace App\Policies\Commercial;

use App\Domain\Commercial\Models\Marche;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class MarchePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'comptable', 'metreur', 'conducteur_travaux');
    }

    public function view(User $user, Marche $marche): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'directeur_technique', 'direction');
    }

    public function update(User $user, Marche $marche): bool
    {
        if ($marche->statut === 'clos') return false;
        return $user->hasRole('admin', 'directeur_technique', 'direction');
    }

    public function delete(User $user, Marche $marche): bool
    {
        if ($marche->statut !== 'brouillon') return false;
        if ($marche->projets()->exists()) return false;
        return $user->hasRole('admin', 'direction');
    }

    /** Signature du marché (DG). */
    public function signer(User $user, Marche $marche): bool
    {
        return $user->hasRole('admin', 'direction')
            && !$marche->est_signe;
    }

    /** Ajouter un avenant. */
    public function ajouterAvenant(User $user, Marche $marche): bool
    {
        return $user->hasRole('admin', 'directeur_technique', 'direction')
            && $marche->statut !== 'clos';
    }

    /** Créer le chantier à partir du marché. */
    public function creerChantier(User $user, Marche $marche): bool
    {
        return $user->hasRole('admin', 'directeur_technique')
            && $marche->est_signe
            && !$marche->projets()->exists();
    }

    /** Générer le PDF du marché. */
    public function pdf(User $user, Marche $marche): bool
    {
        return $this->view($user, $marche);
    }
}