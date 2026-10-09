<?php
namespace App\Policies\Socle;

use App\Domain\Socle\Models\{Communication, User};
use App\Policies\BasePolicy;

class CommunicationPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique');
    }

    public function view(User $user, Communication $communication): bool
    {
        if ($user->id === $communication->expediteur_id) return true;
        return $user->hasRole('admin', 'direction', 'directeur_technique');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'comptable', 'rh', 'responsable_achat');
    }

    public function envoyer(User $user, Communication $communication): bool
    {
        if ($communication->statut === 'envoye') return false;
        return $this->view($user, $communication);
    }

    public function delete(User $user, Communication $communication): bool
    {
        return $user->hasRole('admin', 'direction');
    }
}