<?php
namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Http\Requests\Stock\{StoreMateriauRequest, UpdateMateriauRequest};
use App\Domain\Approvisionnement\Models\Materiau;
use App\Domain\Approvisionnement\Services\MateriauService;
use Illuminate\Http\Request;

class MateriauController extends Controller
{
    public function __construct(private MateriauService $service) {}

    public function index(Request $request)
    {
        $materiaux = $this->service->paginate($request->only(['search', 'categorie_id']));
        return view('logistique.materiaux.index', compact('materiaux'));
    }

    public function create()
    {
        $this->authorize('create', Materiau::class);
        return view('logistique.materiaux.create');
    }

    public function store(StoreMateriauRequest $request)
    {
        $m = $this->service->creer($request->validated());
        return redirect()->route('logistique.materiaux.index')->with('success', "Matériau « {$m->nom} » créé.");
    }

    public function edit(Materiau $materiau)
    {
        $this->authorize('update', $materiau);
        return view('logistique.materiaux.edit', compact('materiau'));
    }

    public function update(UpdateMateriauRequest $request, Materiau $materiau)
    {
        $this->service->mettreAJour($materiau, $request->validated());
        return redirect()->route('logistique.materiaux.index')->with('success', 'Matériau mis à jour.');
    }

    public function destroy(Materiau $materiau)
    {
        $this->authorize('delete', $materiau);
        $this->service->desactiver($materiau);
        return back()->with('success', 'Matériau désactivé.');
    }
}