<?php
namespace App\Policies\Commercial;

use App\Domain\Commercial\Models\Devis;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class DevisPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'comptable', 'metreur', 'conducteur_travaux');
    }

    public function view(User $user, Devis $devis): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'directeur_technique', 'metreur', 'conducteur_travaux');
    }

    public function update(User $user, Devis $devis): bool
    {
        // RG-C01 : Un devis accepté n'est plus modifiable
        if ($devis->statut === 'accepte') return false;
        if ($devis->statut === 'refuse') return false;
        return $user->hasRole('admin', 'directeur_technique', 'metreur', 'conducteur_travaux');
    }

    public function delete(User $user, Devis $devis): bool
    {
        // Seuls les brouillons sont supprimables
        if (!in_array($devis->statut, ['brouillon', 'refuse', 'expire'], true)) return false;
        return $user->hasRole('admin', 'directeur_technique', 'direction');
    }

    /** Valider techniquement un devis (DT). */
    public function validerTechnique(User $user, Devis $devis): bool
    {
        return $user->hasRole('admin', 'directeur_technique', 'direction')
            && $devis->statut === 'brouillon';
    }

    /** Envoyer le devis au client. */
    public function envoyer(User $user, Devis $devis): bool
    {
        return $user->hasRole('admin', 'directeur_technique', 'metreur')
            && $devis->statut === 'brouillon';
    }

    /** Accepter un devis (client ou direction). */
    public function accepter(User $user, Devis $devis): bool
    {
        return $user->hasRole('admin', 'directeur_technique', 'direction')
            && $devis->statut === 'envoye';
    }

    public function refuser(User $user, Devis $devis): bool
    {
        return $user->hasRole('admin', 'directeur_technique', 'direction')
            && $devis->statut === 'envoye';
    }

    public function dupliquer(User $user, Devis $devis): bool
    {
        return $this->create($user);
    }
}