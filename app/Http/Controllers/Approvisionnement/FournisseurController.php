<?php
namespace App\Http\Controllers\Approvisionnement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Approvisionnement\{StoreFournisseurRequest, UpdateFournisseurRequest};
use App\Domain\Approvisionnement\Models\Fournisseur;
use App\Domain\Approvisionnement\Services\FournisseurService;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    public function __construct(private FournisseurService $service) {}

    public function index(Request $request)
    {
        $fournisseurs = $this->service->paginate($request->only(['search', 'categorie']));
        return view('logistique.fournisseurs.index', compact('fournisseurs'));
    }

    public function create()
    {
        $this->authorize('create', Fournisseur::class);
        return view('logistique.fournisseurs.create');
    }

    public function store(StoreFournisseurRequest $request)
    {
        $f = $this->service->creer($request->validated());
        return redirect()->route('logistique.fournisseurs.index')->with('success', "Fournisseur « {$f->nom} » créé.");
    }

    public function edit(Fournisseur $fournisseur)
    {
        $this->authorize('update', $fournisseur);
        return view('logistique.fournisseurs.edit', compact('fournisseur'));
    }

    public function update(UpdateFournisseurRequest $request, Fournisseur $fournisseur)
    {
        $this->service->mettreAJour($fournisseur, $request->validated());
        return redirect()->route('logistique.fournisseurs.index')->with('success', 'Fournisseur mis à jour.');
    }

    public function destroy(Fournisseur $fournisseur)
    {
        $this->authorize('delete', $fournisseur);
        $this->service->desactiver($fournisseur);
        return back()->with('success', 'Fournisseur désactivé.');
    }
}