<?php
namespace App\Policies\Commercial;

use App\Domain\Commercial\Models\CautionMarche;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class CautionMarchePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'comptable');
    }

    public function view(User $user, CautionMarche $caution): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'comptable');
    }

    public function update(User $user, CautionMarche $caution): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'comptable');
    }

    public function delete(User $user, CautionMarche $caution): bool
    {
        return $user->hasRole('admin', 'direction', 'comptable');
    }

    public function liberer(User $user, CautionMarche $caution): bool
    {
        return $user->hasRole('admin', 'direction', 'comptable');
    }
}