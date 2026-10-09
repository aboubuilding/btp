<?php
namespace App\Policies\Personnel;

use App\Domain\Personnel\Models\DocumentEmploye;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class DocumentEmployePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'rh', 'responsable_qhse');
    }

    public function view(User $user, DocumentEmploye $document): bool
    {
        if ($user->employe?->id === $document->employee_id) return true;
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'rh');
    }

    public function update(User $user, DocumentEmploye $document): bool
    {
        return $user->hasRole('admin', 'rh');
    }

    public function delete(User $user, DocumentEmploye $document): bool
    {
        return $user->hasRole('admin', 'direction', 'rh');
    }
}