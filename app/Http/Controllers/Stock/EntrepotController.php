<?php
namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Stock\StoreEntrepotRequest;
use App\Domain\Approvisionnement\Models\Entrepot;

class EntrepotController extends Controller
{
    use HandlesModals;

    public function index()
    {
        $entrepots = Entrepot::with('projet')->withCount('niveaux')->where('etat', 1)->get();
        return view('logistique.entrepots.index', compact('entrepots'));
    }

    public function create()
    {
        $this->authorize('create', Entrepot::class);
        return view('logistique.entrepots.partials._form', ['entrepot' => new Entrepot()]);
    }

    public function store(StoreEntrepotRequest $request)
    {
        $e = Entrepot::create($request->validated());
        return $this->modalSuccess("Dépôt « {$e->nom} » créé.");
    }

    public function edit(Entrepot $entrepot)
    {
        $this->authorize('update', $entrepot);
        return view('logistique.entrepots.partials._form', compact('entrepot'));
    }

    public function update(StoreEntrepotRequest $request, Entrepot $entrepot)
    {
        $entrepot->update($request->validated());
        return $this->modalSuccess('Dépôt mis à jour.');
    }

    public function destroy(Entrepot $entrepot)
    {
        $this->authorize('delete', $entrepot);
        $entrepot->update(['etat' => 0]);
        return $this->modalSuccess('Dépôt désactivé.');
    }
}