<?php
namespace App\Http\Controllers\Chantier;

use App\Http\Controllers\Controller;
use App\Http\Requests\Execution\{StoreTacheRequest, UpdateTacheRequest};
use App\Domain\Execution\Models\{Projet, Tache};
use App\Domain\Execution\Services\TacheService;

class TacheController extends Controller
{
    public function __construct(private TacheService $service) {}

    public function index(Projet $projet)
    {
        $this->authorize('view', $projet);
        $taches = $projet->taches()->with(['phase', 'assigne'])->get();
        return view('chantiers.taches.index', compact('projet', 'taches'));
    }

    public function create(Projet $projet)
    {
        $this->authorize('create', Tache::class);
        return view('chantiers.taches.create', compact('projet'));
    }

    public function store(StoreTacheRequest $request, Projet $projet)
    {
        $this->service->creer($projet, $request->validated());
        return redirect()->route('projets.show', $projet)->with('success', 'Tâche créée.');
    }

    public function edit(Tache $tache)
    {
        $this->authorize('update', $tache);
        return view('chantiers.taches.edit', compact('tache'));
    }

    public function update(UpdateTacheRequest $request, Tache $tache)
    {
        $this->service->mettreAJour($tache, $request->validated());
        return redirect()->route('projets.show', $tache->projet)->with('success', 'Tâche mise à jour.');
    }

    public function mettreAJourAvancement(\Illuminate\Http\Request $request, Tache $tache)
    {
        $this->authorize('mettreAJourAvancement', $tache);
        $this->service->mettreAJourAvancement($tache, (int) $request->input('pourcentage_avancement', 0));
        return response()->json(['success' => true]);
    }

    public function destroy(Tache $tache)
    {
        $this->authorize('delete', $tache);
        $this->service->supprimer($tache);
        return back()->with('success', 'Tâche supprimée.');
    }
}