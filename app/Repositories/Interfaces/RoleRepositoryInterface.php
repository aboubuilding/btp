<?php

namespace App\Repositories\Interfaces;

use App\Models\Role;

interface RoleRepositoryInterface extends BaseRepositoryInterface
{
    public function findBySlug(string $slug): ?Role;
    public function getActiveRoles(): array;
}
