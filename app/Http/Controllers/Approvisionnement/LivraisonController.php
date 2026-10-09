<?php
namespace App\Http\Controllers\Approvisionnement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Approvisionnement\StoreLivraisonRequest;
use App\Domain\Approvisionnement\Models\Livraison;
use App\Domain\Approvisionnement\Services\LivraisonService;
use App\Domain\Socle\Exceptions\RegleGestionException;
use Illuminate\Http\Request;

class LivraisonController extends Controller
{
    public function __construct(private LivraisonService $service) {}

    public function index(Request $request)
    {
        $livraisons = $this->service->paginate($request->only(['statut', 'entrepot_id', 'bon_commande_id']));
        return view('logistique.livraisons.index', compact('livraisons'));
    }

    public function create()
    {
        $this->authorize('create', Livraison::class);
        return view('logistique.livraisons.create');
    }

    public function store(StoreLivraisonRequest $request)
    {
        try {
            $livraison = $this->service->creer($request->validated(), $request->input('articles', []), auth()->id());
            return redirect()->route('logistique.livraisons.show', $livraison)->with('success', 'Livraison enregistrée.');
        } catch (RegleGestionException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(Livraison $livraison)
    {
        $this->authorize('view', $livraison);
        $livraison = $this->service->avecDetails($livraison);
        return view('logistique.livraisons.show', compact('livraison'));
    }

    public function refuser(Request $request, Livraison $livraison)
    {
        $this->authorize('refuser', $livraison);
        $livraison->update(['statut' => 'refusee']);
        return back()->with('success', 'Livraison refusée.');
    }
}