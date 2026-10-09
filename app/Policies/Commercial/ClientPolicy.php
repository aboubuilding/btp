<?php
namespace App\Policies\Commercial;

use App\Domain\Commercial\Models\Client;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class ClientPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'comptable', 'metreur', 'conducteur_travaux');
    }

    public function view(User $user, Client $client): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'metreur', 'conducteur_travaux');
    }

    public function update(User $user, Client $client): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique', 'metreur', 'conducteur_travaux');
    }

    public function delete(User $user, Client $client): bool
    {
        if ($client->projets()->exists() || $client->marches()->exists()) return false;
        return $user->hasRole('admin', 'direction', 'directeur_technique');
    }

    public function desactiver(User $user, Client $client): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique');
    }
}