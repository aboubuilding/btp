<?php
namespace App\Http\Controllers\Materiel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Materiel\{StoreEquipementRequest, UpdateEquipementRequest};
use App\Domain\ParcMateriel\Models\Equipement;
use App\Domain\ParcMateriel\Services\EquipementService;
use Illuminate\Http\Request;

class EquipementController extends Controller
{
    public function __construct(private EquipementService $service) {}

    public function index(Request $request)
    {
        $equipements = $this->service->paginate($request->only(['search', 'statut', 'categorie_id', 'propriete']));
        return view('materiel.equipements.index', compact('equipements'));
    }

    public function create()
    {
        $this->authorize('create', Equipement::class);
        return view('materiel.equipements.create');
    }

    public function store(StoreEquipementRequest $request)
    {
        $e = $this->service->creer($request->validated());
        return redirect()->route('materiel.equipements.show', $e)->with('success', 'Équipement créé.');
    }

    public function show(Equipement $equipement)
    {
        $this->authorize('view', $equipement);
        $equipement = $this->service->avecDetails($equipement);
        return view('materiel.equipements.show', compact('equipement'));
    }

    public function edit(Equipement $equipement)
    {
        $this->authorize('update', $equipement);
        return view('materiel.equipements.edit', compact('equipement'));
    }

    public function update(UpdateEquipementRequest $request, Equipement $equipement)
    {
        $this->service->mettreAJour($equipement, $request->validated());
        return redirect()->route('materiel.equipements.show', $equipement)->with('success', 'Équipement mis à jour.');
    }

    public function changerStatut(Request $request, Equipement $equipement)
    {
        $this->authorize('changerStatut', $equipement);
        $this->service->changerStatut($equipement, $request->input('statut'));
        return back()->with('success', 'Statut modifié.');
    }

    public function destroy(Equipement $equipement)
    {
        $this->authorize('delete', $equipement);
        $this->service->desactiver($equipement);
        return redirect()->route('materiel.equipements.index')->with('success', 'Équipement désactivé.');
    }
}