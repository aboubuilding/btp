<?php
namespace App\Policies\Stock;

use App\Domain\Approvisionnement\Models\Materiau;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class MateriauPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'responsable_achat', 'magasinier', 'metreur', 'conducteur_travaux', 'chef_chantier');
    }

    public function view(User $user, Materiau $materiau): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'responsable_achat', 'direction');
    }

    public function update(User $user, Materiau $materiau): bool
    {
        return $user->hasRole('admin', 'responsable_achat', 'direction');
    }

    public function delete(User $user, Materiau $materiau): bool
    {
        if ($materiau->mouvements()->exists()) return false;
        return $user->hasRole('admin', 'direction');
    }
}