<?php

namespace App\Repositories\Eloquent;

use App\Models\Role;
use App\Repositories\Interfaces\RoleRepositoryInterface;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    public function model(): string
    {
        return Role::class;
    }

    public function findBySlug(string $slug): ?Role
    {
        return $this->activeQuery()->where('slug', $slug)->first();
    }

    public function getActiveRoles(): array
    {
        return $this->activeQuery()
            ->orderBy('nom')
            ->get()
            ->toArray();
    }
}
