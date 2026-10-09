<?php
namespace App\Http\Controllers\Chantier;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Execution\StoreJalonRequest;
use App\Domain\Execution\Models\{JalonProjet, Projet};

class JalonController extends Controller
{
    use HandlesModals;

    public function create(Projet $projet)
    {
        $this->authorize('update', $projet);
        return view('chantiers.jalons.partials._form', [
            'projet' => $projet,
            'jalon'  => new JalonProjet(),
        ]);
    }

    public function store(StoreJalonRequest $request, Projet $projet)
    {
        $jalon = $projet->jalons()->create($request->validated());
        return $this->modalSuccess("Jalon « {$jalon->libelle} » créé.");
    }

    public function edit(JalonProjet $jalon)
    {
        $this->authorize('update', $jalon);
        return view('chantiers.jalons.partials._form', [
            'projet' => $jalon->projet,
            'jalon'  => $jalon,
        ]);
    }

    public function update(StoreJalonRequest $request, JalonProjet $jalon)
    {
        $jalon->update($request->validated());
        return $this->modalSuccess('Jalon mis à jour.');
    }

    public function destroy(JalonProjet $jalon)
    {
        $this->authorize('delete', $jalon);
        $jalon->update(['etat' => 0]);
        return $this->modalSuccess('Jalon supprimé.');
    }
}