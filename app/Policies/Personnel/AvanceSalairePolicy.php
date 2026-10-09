<?php
namespace App\Policies\Personnel;

use App\Domain\Personnel\Models\AvanceSalaire;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class AvanceSalairePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'rh');
    }

    public function view(User $user, AvanceSalaire $avance): bool
    {
        if ($user->employe?->id === $avance->employee_id) return true;
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'rh');
    }

    public function update(User $user, AvanceSalaire $avance): bool
    {
        if ($avance->statut !== 'demande') return false;
        return $user->hasRole('admin', 'rh');
    }

    public function approuver(User $user, AvanceSalaire $avance): bool
    {
        return $user->hasRole('admin', 'rh', 'direction')
            && $avance->statut === 'demande';
    }

    public function rembourser(User $user, AvanceSalaire $avance): bool
    {
        return $user->hasRole('admin', 'rh', 'comptable')
            && $avance->statut === 'approuvee';
    }

    public function delete(User $user, AvanceSalaire $avance): bool
    {
        if ($avance->statut !== 'demande') return false;
        return $user->hasRole('admin', 'direction');
    }
}