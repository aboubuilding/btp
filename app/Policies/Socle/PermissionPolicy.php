<?php
namespace App\Policies\Socle;

use App\Domain\Socle\Models\{Permission, User};
use App\Policies\BasePolicy;

class PermissionPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction');
    }

    public function view(User $user, Permission $permission): bool
    {
        return $user->hasRole('admin', 'direction');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Permission $permission): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Permission $permission): bool
    {
        return $user->hasRole('admin') && !$permission->roles()->exists();
    }
}