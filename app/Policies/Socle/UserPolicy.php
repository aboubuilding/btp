<?php
namespace App\Policies\Socle;

use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class UserPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin', 'direction');
    }

    public function view(User $user, User $target): bool
    {
        if ($user->id === $target->id) return true;
        return $user->hasRole('admin', 'direction');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, User $target): bool
    {
        if ($user->id === $target->id) return true;
        return $user->hasRole('admin');
    }

    public function delete(User $user, User $target): bool
    {
        return $user->hasRole('admin') && $user->id !== $target->id;
    }

    public function resetPassword(User $user, User $target): bool
    {
        return $user->hasRole('admin');
    }

    public function toggleActif(User $user, User $target): bool
    {
        return $user->hasRole('admin') && $user->id !== $target->id;
    }
}