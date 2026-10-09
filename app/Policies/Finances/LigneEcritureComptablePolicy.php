<?php
namespace App\Policies\Finances;

use App\Domain\Finances\Models\LigneEcritureComptable;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class LigneEcritureComptablePolicy extends BasePolicy
{
    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'comptable');
    }

    public function update(User $user, LigneEcritureComptable $ligne): bool
    {
        return $ligne->ecriture->statut === 'brouillon'
            && $user->hasRole('admin', 'comptable');
    }

    public function delete(User $user, LigneEcritureComptable $ligne): bool
    {
        return $ligne->ecriture->statut === 'brouillon'
            && $user->hasRole('admin', 'comptable');
    }
}