<?php
namespace App\Policies\Finances;

use App\Domain\Finances\Models\ExerciceFiscal;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class ExerciceFiscalPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'comptable');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'comptable');
    }

    public function update(User $user, ExerciceFiscal $exercice): bool
    {
        if ($exercice->statut === 'cloture') return false;
        return $user->hasRole('admin', 'comptable');
    }

    public function cloturer(User $user, ExerciceFiscal $exercice): bool
    {
        return $user->hasRole('admin', 'comptable', 'direction')
            && $exercice->statut === 'ouvert';
    }

    public function delete(User $user, ExerciceFiscal $exercice): bool
    {
        if ($exercice->ecritures()->exists()) return false;
        return $user->hasRole('admin', 'direction');
    }
}