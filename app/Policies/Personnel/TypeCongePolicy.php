<?php
namespace App\Policies\Personnel;

use App\Domain\Personnel\Models\TypeConge;
use App\Domain\Socle\Models\User;
use App\Policies\BasePolicy;

class TypeCongePolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'rh', 'direction');
    }

    public function update(User $user, TypeConge $type): bool
    {
        return $user->hasRole('admin', 'rh', 'direction');
    }

    public function delete(User $user, TypeConge $type): bool
    {
        if ($type->demandes()->exists()) return false;
        return $user->hasRole('admin', 'direction');
    }
}