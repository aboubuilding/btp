<?php
namespace App\Policies\Socle;

use App\Domain\Socle\Models\{Parametre, User};
use App\Policies\BasePolicy;

class ParametrePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction');
    }

    public function view(User $user, Parametre $parametre): bool
    {
        return $user->hasRole('admin', 'direction');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Parametre $parametre): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Parametre $parametre): bool
    {
        return $user->hasRole('admin');
    }
}