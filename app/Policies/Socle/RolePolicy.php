<?php
namespace App\Policies\Socle;

use App\Domain\Socle\Models\{Role, User};
use App\Policies\BasePolicy;

class RolePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction');
    }

    public function view(User $user, Role $role): bool
    {
        return $user->hasRole('admin', 'direction');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Role $role): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Role $role): bool
    {
        if ($role->slug === 'admin') return false;
        if ($role->users()->exists()) return false;
        return $user->hasRole('admin');
    }

    public function synchroniserPermissions(User $user, Role $role): bool
    {
        return $user->hasRole('admin');
    }
}