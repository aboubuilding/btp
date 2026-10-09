<?php
namespace App\Http\Controllers\SousTraitance;

use App\Http\Controllers\Controller;
use App\Http\Requests\SousTraitance\{StoreSoustraitantRequest, UpdateSoustraitantRequest, BlacklisterSoustraitantRequest};
use App\Domain\SousTraitance\Models\Soustraitant;
use App\Domain\SousTraitance\Services\SoustraitantService;
use Illuminate\Http\Request;

class SoustraitantController extends Controller
{
    public function __construct(private SoustraitantService $service) {}

    public function index(Request $request)
    {
        $soustraitants = $this->service->paginate($request->only(['search', 'statut', 'specialite']));
        return view('soustraitance.soustraitants.index', compact('soustraitants'));
    }

    public function create()
    {
        $this->authorize('create', Soustraitant::class);
        return view('soustraitance.soustraitants.create');
    }

    public function store(StoreSoustraitantRequest $request)
    {
        $st = $this->service->creer($request->validated());
        return redirect()->route('soustraitance.soustraitants.show', $st)->with('success', 'Sous-traitant créé.');
    }

    public function show(Soustraitant $soustraitant)
    {
        $this->authorize('view', $soustraitant);
        $soustraitant = $this->service->avecDetails($soustraitant);
        return view('soustraitance.soustraitants.show', compact('soustraitant'));
    }

    public function edit(Soustraitant $soustraitant)
    {
        $this->authorize('update', $soustraitant);
        return view('soustraitance.soustraitants.edit', compact('soustraitant'));
    }

    public function update(UpdateSoustraitantRequest $request, Soustraitant $soustraitant)
    {
        $this->service->mettreAJour($soustraitant, $request->validated());
        return redirect()->route('soustraitance.soustraitants.show', $soustraitant)->with('success', 'Sous-traitant mis à jour.');
    }

    public function blacklister(BlacklisterSoustraitantRequest $request, Soustraitant $soustraitant)
    {
        $this->service->blacklister($soustraitant);
        return back()->with('success', 'Sous-traitant blacklisté.');
    }

    public function destroy(Soustraitant $soustraitant)
    {
        $this->authorize('delete', $soustraitant);
        $soustraitant->update(['etat' => 0]);
        return back()->with('success', 'Sous-traitant désactivé.');
    }
}