<?php
namespace App\Policies\Approvisionnement;

use App\Domain\Approvisionnement\Models\BonCommande;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class BonCommandePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'comptable', 'responsable_achat', 'magasinier');
    }

    public function view(User $user, BonCommande $bc): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'responsable_achat', 'directeur_technique');
    }

    public function update(User $user, BonCommande $bc): bool
    {
        if ($bc->est_fige) return false;
        return $user->hasRole('admin', 'responsable_achat', 'directeur_technique');
    }

    public function delete(User $user, BonCommande $bc): bool
    {
        if ($bc->est_fige) return false;
        return $user->hasRole('admin', 'responsable_achat', 'direction');
    }

    /** Envoyer le BC au fournisseur. */
    public function envoyer(User $user, BonCommande $bc): bool
    {
        return $user->hasRole('admin', 'responsable_achat')
            && $bc->statut === 'brouillon';
    }

    /** Valider un BC au-delà du seuil (DG). */
    public function validerDirection(User $user, BonCommande $bc): bool
    {
        return $user->hasRole('admin', 'direction');
    }

    /** Annuler un BC. */
    public function annuler(User $user, BonCommande $bc): bool
    {
        if ($bc->est_fige) return false;
        return $user->hasRole('admin', 'direction', 'responsable_achat');
    }
}