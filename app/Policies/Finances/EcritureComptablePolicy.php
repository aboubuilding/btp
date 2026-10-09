<?php
namespace App\Policies\Finances;

use App\Domain\Finances\Models\EcritureComptable;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class EcritureComptablePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'comptable');
    }

    public function view(User $user, EcritureComptable $ecriture): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'comptable');
    }

    public function update(User $user, EcritureComptable $ecriture): bool
    {
        if ($ecriture->statut === 'validee') return false;
        return $user->hasRole('admin', 'comptable');
    }

    public function valider(User $user, EcritureComptable $ecriture): bool
    {
        return $user->hasRole('admin', 'comptable', 'direction')
            && $ecriture->statut === 'brouillon';
    }

    public function delete(User $user, EcritureComptable $ecriture): bool
    {
        if ($ecriture->statut === 'validee') return false;
        return $user->hasRole('admin', 'direction');
    }
}