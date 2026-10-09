<?php
namespace App\Http\Controllers\Chantier;

use App\Http\Controllers\Controller;
use App\Http\Requests\Execution\{StoreProjetRequest, UpdateProjetRequest};
use App\Domain\Execution\Models\Projet;
use App\Domain\Execution\Services\ProjetService;
use App\Domain\Execution\Repositories\ProjetRepositoryInterface;
use Illuminate\Http\Request;

class ProjetController extends Controller
{
    public function __construct(
        private ProjetService $service,
        private ProjetRepositoryInterface $repo,
    ) {}

    public function index(Request $request)
    {
        $projets = $this->repo->paginateAvecRelations($request->only(['search', 'statut', 'type', 'client_id']));
        return view('chantiers.projets.index', compact('projets'));
    }

    public function create()
    {
        $this->authorize('create', Projet::class);
        return view('chantiers.projets.create');
    }

    public function store(StoreProjetRequest $request)
    {
        $projet = $this->service->creer($request->validated(), auth()->id());
        return redirect()->route('projets.show', $projet)->with('success', 'Chantier créé.');
    }

    public function show(Projet $projet)
    {
        $this->authorize('view', $projet);
        $projet = $this->repo->avecDetails($projet);
        return view('chantiers.projets.show', compact('projet'));
    }

    public function edit(Projet $projet)
    {
        $this->authorize('update', $projet);
        return view('chantiers.projets.edit', compact('projet'));
    }

    public function update(UpdateProjetRequest $request, Projet $projet)
    {
        $this->service->mettreAJour($projet, $request->validated());
        return redirect()->route('projets.show', $projet)->with('success', 'Chantier mis à jour.');
    }

    public function demarrer(Projet $projet)
    {
        $this->authorize('demarrer', $projet);
        $this->service->demarrer($projet);
        return back()->with('success', 'Chantier démarré.');
    }

    public function suspendre(Request $request, Projet $projet)
    {
        $this->authorize('suspendre', $projet);
        $this->service->suspendre($projet, $request->input('motif', ''));
        return back()->with('success', 'Chantier suspendu.');
    }

    public function terminer(Projet $projet)
    {
        $this->authorize('terminer', $projet);
        $this->service->terminer($projet);
        return back()->with('success', 'Chantier terminé.');
    }

    public function destroy(Projet $projet)
    {
        $this->authorize('delete', $projet);
        $this->repo->desactiver($projet);
        return redirect()->route('projets.index')->with('success', 'Chantier désactivé.');
    }
}