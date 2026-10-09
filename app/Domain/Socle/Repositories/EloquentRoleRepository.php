<?php
namespace App\Domain\Socle\Repositories;

use App\Domain\Socle\Models\Role;
use Illuminate\Support\Collection;

class EloquentRoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    protected array $colonnesSearch = ['nom', 'slug', 'description'];
    protected string $orderBy = 'nom';
    protected string $orderDir = 'asc';

    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    public function findBySlug(string $slug): ?Role
    {
        return $this->newQuery()->where('slug', $slug)->first();
    }

    public function avecPermissions(): Collection
    {
        return $this->model->with('permissions')->where('etat', 1)->orderBy('nom')->get();
    }

    public function avecUtilisateurs(): Collection
    {
        return $this->model->withCount('users')->where('etat', 1)->orderBy('nom')->get();
    }

    public function synchroniserPermissions(Role $role, array $permissionIds): void
    {
        $role->permissions()->sync($permissionIds);
    }
}