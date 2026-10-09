<?php
namespace App\Http\Controllers\Commercial;

use App\Http\Controllers\Controller;
use App\Http\Requests\Commercial\{StoreMarcheRequest, UpdateMarcheRequest, SignerMarcheRequest};
use App\Domain\Commercial\Models\Marche;
use App\Domain\Commercial\Services\MarcheService;
use Illuminate\Http\Request;

class MarcheController extends Controller
{
    public function __construct(private MarcheService $service) {}

    public function index(Request $request)
    {
        $marches = $this->service->paginate($request->only(['search', 'statut', 'client_id']));
        return view('commercial.marches.index', compact('marches'));
    }

    public function create()
    {
        $this->authorize('create', Marche::class);
        return view('commercial.marches.create');
    }

    public function store(StoreMarcheRequest $request)
    {
        $marche = $this->service->creer($request->validated());
        return redirect()->route('commercial.marches.show', $marche)->with('success', 'Marché créé.');
    }

    public function show(Marche $marche)
    {
        $this->authorize('view', $marche);
        $marche = $this->service->avecDetails($marche);
        return view('commercial.marches.show', compact('marche'));
    }

    public function edit(Marche $marche)
    {
        $this->authorize('update', $marche);
        return view('commercial.marches.edit', compact('marche'));
    }

    public function update(UpdateMarcheRequest $request, Marche $marche)
    {
        $this->service->mettreAJour($marche, $request->validated());
        return redirect()->route('commercial.marches.show', $marche)->with('success', 'Marché mis à jour.');
    }

    public function signer(SignerMarcheRequest $request, Marche $marche)
    {
        $this->service->signer($marche);
        return back()->with('success', 'Marché signé.');
    }

    public function creerChantier(Marche $marche)
    {
        $this->authorize('creerChantier', $marche);
        return redirect()->route('projets.create', ['marche_id' => $marche->id]);
    }

    public function destroy(Marche $marche)
    {
        $this->authorize('delete', $marche);
        $marche->update(['etat' => 0]);
        return redirect()->route('commercial.marches.index')->with('success', 'Marché supprimé.');
    }
}