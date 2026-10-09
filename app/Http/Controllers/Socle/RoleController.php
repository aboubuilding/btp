<?php
namespace App\Http\Controllers\Socle;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Socle\StoreRoleRequest;
use App\Domain\Socle\Models\{Role, Permission};
use App\Domain\Socle\Repositories\RoleRepositoryInterface;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    use HandlesModals;

    public function __construct(private RoleRepositoryInterface $roles) {}

    public function index()
    {
        $roles = $this->roles->avecUtilisateurs();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $this->authorize('create', Role::class);
        return view('admin.roles.partials._form', ['role' => new Role()]);
    }

    public function store(StoreRoleRequest $request)
    {
        $role = $this->roles->create($request->validated());
        return $this->modalSuccess("Rôle « {$role->nom} » créé.");
    }

    public function edit(Role $role)
    {
        $this->authorize('update', $role);
        return view('admin.roles.partials._form', compact('role'));
    }

    public function update(StoreRoleRequest $request, Role $role)
    {
        $this->roles->update($role, $request->validated());
        return $this->modalSuccess('Rôle mis à jour.');
    }

    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);
        $this->roles->desactiver($role);
        return $this->modalSuccess('Rôle désactivé.');
    }

    public function permissions(Role $role)
    {
        $this->authorize('synchroniserPermissions', $role);
        $permissions = Permission::where('etat', 1)->orderBy('module')->orderBy('slug')->get();
        return view('admin.roles.permissions', compact('role', 'permissions'));
    }

    public function synchroniser(Request $request, Role $role)
    {
        $this->authorize('synchroniserPermissions', $role);
        $ids = $request->input('permissions', []);
        $this->roles->synchroniserPermissions($role, $ids);
        return back()->with('success', 'Permissions mises à jour.');
    }
}