<?php
namespace App\Policies\Execution;

use App\Domain\Execution\Models\Projet;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class ProjetPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return true; // tous les profils ont accès à la liste (filtrée)
    }

    public function view(User $user, Projet $projet): bool
    {
        return $this->aAccesChantier($user, $projet);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'directeur_technique');
    }

    public function update(User $user, Projet $projet): bool
    {
        if ($this->estDirection($user)) return true;
        return $this->estResponsableChantier($user, $projet);
    }

    public function delete(User $user, Projet $projet): bool
    {
        if ($projet->statut === 'en_cours') return false;
        return $user->hasRole('admin', 'direction');
    }

    /** Démarrer un chantier. */
    public function demarrer(User $user, Projet $projet): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique')
            && $projet->statut === 'planifie';
    }

    /** Suspendre un chantier. */
    public function suspendre(User $user, Projet $projet): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique')
            && $projet->statut === 'en_cours';
    }

    /** Terminer un chantier. */
    public function terminer(User $user, Projet $projet): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique')
            && $projet->statut === 'en_cours';
    }

    /** Clôturer un chantier (validation DG). */
    public function cloturer(User $user, Projet $projet): bool
    {
        return $user->hasRole('admin', 'direction')
            && $projet->statut === 'termine';
    }

    /** Pointer sur ce chantier. */
    public function pointer(User $user, Projet $projet): bool
    {
        return $user->hasRole('admin', 'directeur_technique', 'conducteur_travaux', 'chef_chantier', 'rh')
            || $this->estMembreChantier($user, $projet);
    }

    /** Recalculer l'avancement. */
    public function recalculerAvancement(User $user, Projet $projet): bool
    {
        return $user->hasRole('admin', 'directeur_technique', 'conducteur_travaux');
    }
}