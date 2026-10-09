<?php
namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Http\Requests\Stock\StoreInventaireRequest;
use App\Domain\Approvisionnement\Models\Inventaire;
use App\Domain\Approvisionnement\Services\InventaireService;
use Illuminate\Http\Request;

class InventaireController extends Controller
{
    public function __construct(private InventaireService $service) {}

    public function index()
    {
        $inventaires = Inventaire::with('entrepot')->withCount('articles')->latest()->paginate(25);
        return view('logistique.inventaires.index', compact('inventaires'));
    }

    public function create()
    {
        $this->authorize('create', Inventaire::class);
        return view('logistique.inventaires.create');
    }

    public function store(StoreInventaireRequest $request)
    {
        $inventaire = $this->service->creer($request->validated());
        return redirect()->route('logistique.inventaires.show', $inventaire)->with('success', 'Inventaire créé.');
    }

    public function show(Inventaire $inventaire)
    {
        $this->authorize('view', $inventaire);
        $inventaire->load(['entrepot', 'articles.materiau', 'validePar']);
        return view('logistique.inventaires.show', compact('inventaire'));
    }

    public function valider(Inventaire $inventaire)
    {
        $this->authorize('valider', $inventaire);
        $this->service->valider($inventaire, auth()->id());
        return back()->with('success', 'Inventaire validé.');
    }
}