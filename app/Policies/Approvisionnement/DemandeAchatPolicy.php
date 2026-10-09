<?php
namespace App\Policies\Approvisionnement;

use App\Domain\Approvisionnement\Models\DemandeAchat;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class DemandeAchatPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'responsable_achat', 'chef_chantier', 'conducteur_travaux', 'responsable_materiel');
    }

    public function view(User $user, DemandeAchat $demande): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'chef_chantier', 'conducteur_travaux', 'responsable_materiel', 'responsable_achat');
    }

    public function update(User $user, DemandeAchat $demande): bool
    {
        if ($demande->statut !== 'en_attente') return false;
        if ($this->estDirection($user)) return true;
        return $demande->demandeur_id === $user->employe?->id;
    }

    public function valider(User $user, DemandeAchat $demande): bool
    {
        return $user->hasRole('admin', 'directeur_technique', 'direction')
            && $demande->statut === 'en_attente';
    }

    public function rejeter(User $user, DemandeAchat $demande): bool
    {
        return $this->valider($user, $demande);
    }

    public function delete(User $user, DemandeAchat $demande): bool
    {
        if ($demande->statut !== 'en_attente') return false;
        return $user->hasRole('admin', 'direction', 'directeur_technique');
    }
}