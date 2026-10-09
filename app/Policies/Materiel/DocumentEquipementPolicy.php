<?php
namespace App\Policies\Materiel;

use App\Domain\ParcMateriel\Models\DocumentEquipement;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class DocumentEquipementPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'responsable_materiel', 'responsable_qhse');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'responsable_materiel');
    }

    public function update(User $user, DocumentEquipement $document): bool
    {
        return $user->hasRole('admin', 'responsable_materiel');
    }

    public function delete(User $user, DocumentEquipement $document): bool
    {
        return $user->hasRole('admin', 'direction', 'responsable_materiel');
    }
}