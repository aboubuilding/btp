<?php
namespace App\Domain\Socle\Repositories;

use App\Domain\Socle\Models\Role;
use Illuminate\Support\Collection;

interface RoleRepositoryInterface extends BaseRepositoryInterface
{
    public function findBySlug(string $slug): ?Role;
    public function avecPermissions(): Collection;
    public function avecUtilisateurs(): Collection;
    public function synchroniserPermissions(Role $role, array $permissionIds): void;
}