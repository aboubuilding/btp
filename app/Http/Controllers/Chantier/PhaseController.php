<?php
namespace App\Http\Controllers\Chantier;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Execution\StorePhaseRequest;
use App\Domain\Execution\Models\{PhaseProjet, Projet};

class PhaseController extends Controller
{
    use HandlesModals;

    public function create(Projet $projet)
    {
        $this->authorize('update', $projet);
        return view('chantiers.phases.partials._form', [
            'projet' => $projet,
            'phase'  => new PhaseProjet(),
        ]);
    }

    public function store(StorePhaseRequest $request, Projet $projet)
    {
        $phase = $projet->phases()->create($request->validated());
        return $this->modalSuccess("Phase « {$phase->nom} » créée.");
    }

    public function edit(PhaseProjet $phase)
    {
        $this->authorize('update', $phase);
        return view('chantiers.phases.partials._form', [
            'projet' => $phase->projet,
            'phase'  => $phase,
        ]);
    }

    public function update(StorePhaseRequest $request, PhaseProjet $phase)
    {
        $phase->update($request->validated());
        return $this->modalSuccess('Phase mise à jour.');
    }

    public function destroy(PhaseProjet $phase)
    {
        $this->authorize('delete', $phase);
        $phase->update(['etat' => 0]);
        return $this->modalSuccess('Phase supprimée.');
    }
}