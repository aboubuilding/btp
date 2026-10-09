<?php
namespace App\Policies\Finances;

use App\Domain\Finances\Models\Depense;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class DepensePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'comptable', 'chef_chantier', 'conducteur_travaux');
    }

    public function view(User $user, Depense $depense): bool
    {
        return $this->aAccesChantier($user, $depense->projet);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'comptable', 'chef_chantier', 'conducteur_travaux');
    }

    public function update(User $user, Depense $depense): bool
    {
        if ($depense->statut !== 'en_attente') return false;
        if ($this->estDirection($user)) return true;
        return $user->hasRole('chef_chantier', 'conducteur_travaux', 'comptable');
    }

    public function approuver(User $user, Depense $depense): bool
    {
        if ($depense->statut !== 'en_attente') return false;
        return $user->hasRole('admin', 'comptable', 'direction', 'directeur_technique');
    }

    public function rejeter(User $user, Depense $depense): bool
    {
        return $this->approuver($user, $depense);
    }

    public function delete(User $user, Depense $depense): bool
    {
        if ($depense->statut === 'approuve') return false;
        return $user->hasRole('admin', 'direction', 'comptable');
    }
}