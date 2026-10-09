<?php
namespace App\Http\Controllers\Materiel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Materiel\StoreCategorieEquipementRequest;
use App\Domain\ParcMateriel\Models\CategorieEquipement;

class CategorieEquipementController extends Controller
{
    use HandlesModals;

    public function index()
    {
        $categories = CategorieEquipement::withCount('equipements')->where('etat', 1)->orderBy('nom')->get();
        return view('materiel.categories.index', compact('categories'));
    }

    public function create()
    {
        $this->authorize('create', CategorieEquipement::class);
        return view('materiel.categories.partials._form', ['categorie' => new CategorieEquipement()]);
    }

    public function store(StoreCategorieEquipementRequest $request)
    {
        $c = CategorieEquipement::create($request->validated());
        return $this->modalSuccess("Catégorie « {$c->nom} » créée.");
    }

    public function edit(CategorieEquipement $categorie)
    {
        $this->authorize('update', $categorie);
        return view('materiel.categories.partials._form', compact('categorie'));
    }

    public function update(StoreCategorieEquipementRequest $request, CategorieEquipement $categorie)
    {
        $categorie->update($request->validated());
        return $this->modalSuccess('Catégorie mise à jour.');
    }

    public function destroy(CategorieEquipement $categorie)
    {
        $this->authorize('delete', $categorie);
        $categorie->update(['etat' => 0]);
        return $this->modalSuccess('Catégorie désactivée.');
    }
}