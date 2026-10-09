<?php
namespace App\Policies\Socle;

use App\Domain\Socle\Models\{CommunicationTemplate, User};
use App\Policies\BasePolicy;

class CommunicationTemplatePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction', 'directeur_technique');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'direction');
    }

    public function update(User $user, CommunicationTemplate $template): bool
    {
        return $user->hasRole('admin', 'direction');
    }

    public function delete(User $user, CommunicationTemplate $template): bool
    {
        return $user->hasRole('admin');
    }
}