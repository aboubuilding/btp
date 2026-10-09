<?php
namespace App\Http\Controllers\Personnel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Personnel\StoreDepartementRequest;
use App\Domain\Personnel\Models\Departement;

class DepartementController extends Controller
{
    use HandlesModals;

    public function create()
    {
        $this->authorize('create', Departement::class);
        return view('rh.departements.partials._form', ['departement' => new Departement()]);
    }

    public function store(StoreDepartementRequest $request)
    {
        $d = Departement::create($request->validated());
        return $this->modalSuccess("Département « {$d->nom} » créé.");
    }

    public function edit(Departement $departement)
    {
        $this->authorize('update', $departement);
        return view('rh.departements.partials._form', compact('departement'));
    }

    public function update(StoreDepartementRequest $request, Departement $departement)
    {
        $departement->update($request->validated());
        return $this->modalSuccess('Département mis à jour.');
    }

    public function destroy(Departement $departement)
    {
        $this->authorize('delete', $departement);
        $departement->update(['etat' => 0]);
        return $this->modalSuccess('Département désactivé.');
    }
}