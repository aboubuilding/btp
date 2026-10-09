<?php
namespace App\Policies\SousTraitance;

use App\Domain\SousTraitance\Models\PaiementSousTraitant;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class PaiementSousTraitantPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'comptable', 'conducteur_travaux');
    }

    public function view(User $user, PaiementSousTraitant $paiement): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'comptable', 'conducteur_travaux');
    }

    public function update(User $user, PaiementSousTraitant $paiement): bool
    {
        return $user->hasRole('admin', 'comptable', 'direction');
    }

    public function delete(User $user, PaiementSousTraitant $paiement): bool
    {
        return $user->hasRole('admin', 'direction');
    }
}