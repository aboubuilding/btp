<?php
namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Stock\StoreCategorieMateriauRequest;
use App\Domain\Approvisionnement\Models\CategorieMateriau;

class CategorieMateriauController extends Controller
{
    use HandlesModals;

    public function index()
    {
        $categories = CategorieMateriau::withCount('materiaux')->where('etat', 1)->orderBy('nom')->get();
        return view('logistique.categories.index', compact('categories'));
    }

    public function create()
    {
        $this->authorize('create', CategorieMateriau::class);
        return view('logistique.categories.partials._form', ['categorie' => new CategorieMateriau()]);
    }

    public function store(StoreCategorieMateriauRequest $request)
    {
        $c = CategorieMateriau::create($request->validated());
        return $this->modalSuccess("Catégorie « {$c->nom} » créée.");
    }

    public function edit(CategorieMateriau $categorie)
    {
        $this->authorize('update', $categorie);
        return view('logistique.categories.partials._form', compact('categorie'));
    }

    public function update(StoreCategorieMateriauRequest $request, CategorieMateriau $categorie)
    {
        $categorie->update($request->validated());
        return $this->modalSuccess('Catégorie mise à jour.');
    }

    public function destroy(CategorieMateriau $categorie)
    {
        $this->authorize('delete', $categorie);
        $categorie->update(['etat' => 0]);
        return $this->modalSuccess('Catégorie désactivée.');
    }
}