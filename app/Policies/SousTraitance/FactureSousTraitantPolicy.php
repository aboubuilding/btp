<?php
namespace App\Policies\SousTraitance;

use App\Domain\SousTraitance\Models\FactureSousTraitant;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class FactureSousTraitantPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'comptable', 'conducteur_travaux');
    }

    public function view(User $user, FactureSousTraitant $facture): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'comptable', 'conducteur_travaux');
    }

    public function update(User $user, FactureSousTraitant $facture): bool
    {
        if ($facture->statut === 'payee') return false;
        return $user->hasRole('admin', 'comptable', 'direction');
    }

    public function valider(User $user, FactureSousTraitant $facture): bool
    {
        return $user->hasRole('admin', 'comptable', 'direction')
            && $facture->statut === 'recue';
    }

    public function delete(User $user, FactureSousTraitant $facture): bool
    {
        if ($facture->paiements()->exists()) return false;
        return $user->hasRole('admin', 'direction');
    }
}