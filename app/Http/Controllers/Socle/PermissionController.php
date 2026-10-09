<?php
namespace App\Http\Controllers\Socle;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Socle\StorePermissionRequest;
use App\Domain\Socle\Models\Permission;

class PermissionController extends Controller
{
    use HandlesModals;

    public function index()
    {
        $permissions = Permission::where('etat', 1)
            ->orderBy('module')
            ->orderBy('slug')
            ->get()
            ->groupBy('module');
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        $this->authorize('create', Permission::class);
        return view('admin.permissions.partials._form', ['permission' => new Permission()]);
    }

    public function store(StorePermissionRequest $request)
    {
        $p = Permission::create($request->validated());
        return $this->modalSuccess("Permission « {$p->nom} » créée.");
    }

    public function edit(Permission $permission)
    {
        $this->authorize('update', $permission);
        return view('admin.permissions.partials._form', compact('permission'));
    }

    public function update(StorePermissionRequest $request, Permission $permission)
    {
        $permission->update($request->validated());
        return $this->modalSuccess('Permission mise à jour.');
    }

    public function destroy(Permission $permission)
    {
        $this->authorize('delete', $permission);
        $permission->update(['etat' => 0]);
        return $this->modalSuccess('Permission désactivée.');
    }
}