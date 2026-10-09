<?php
namespace App\Policies\Personnel;

use App\Domain\Personnel\Models\PeriodePaie;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class PeriodePaiePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'rh', 'comptable');
    }

    public function view(User $user, PeriodePaie $periode): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'rh');
    }

    public function update(User $user, PeriodePaie $periode): bool
    {
        if ($periode->statut !== 'ouverte') return false;
        return $user->hasRole('admin', 'rh');
    }

    public function generer(User $user, PeriodePaie $periode): bool
    {
        return $user->hasRole('admin', 'rh')
            && $periode->statut === 'ouverte';
    }

    public function cloturer(User $user, PeriodePaie $periode): bool
    {
        return $user->hasRole('admin', 'rh', 'direction')
            && $periode->statut === 'ouverte';
    }

    public function delete(User $user, PeriodePaie $periode): bool
    {
        if ($periode->bulletins()->exists()) return false;
        if ($periode->statut !== 'ouverte') return false;
        return $user->hasRole('admin', 'direction');
    }
}