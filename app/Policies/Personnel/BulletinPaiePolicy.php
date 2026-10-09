<?php
namespace App\Policies\Personnel;

use App\Domain\Personnel\Models\BulletinPaie;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class BulletinPaiePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'rh', 'comptable');
    }

    public function view(User $user, BulletinPaie $bulletin): bool
    {
        // L'employé peut voir son propre bulletin
        if ($user->employe?->id === $bulletin->employee_id) return true;
        return $this->viewAny($user);
    }

    public function valider(User $user, BulletinPaie $bulletin): bool
    {
        return $user->hasRole('admin', 'rh')
            && $bulletin->statut === 'brouillon';
    }

    public function marquerPaye(User $user, BulletinPaie $bulletin): bool
    {
        return $user->hasRole('admin', 'rh', 'comptable')
            && $bulletin->statut === 'valide';
    }

    public function pdf(User $user, BulletinPaie $bulletin): bool
    {
        return $this->view($user, $bulletin);
    }

    public function delete(User $user, BulletinPaie $bulletin): bool
    {
        if ($bulletin->statut !== 'brouillon') return false;
        return $user->hasRole('admin', 'direction');
    }
}