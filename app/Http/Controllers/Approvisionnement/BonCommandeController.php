<?php
namespace App\Http\Controllers\Approvisionnement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Approvisionnement\{StoreBonCommandeRequest, ValiderBonCommandeRequest};
use App\Domain\Approvisionnement\Models\BonCommande;
use App\Domain\Approvisionnement\Services\BonCommandeService;
use Illuminate\Http\Request;

class BonCommandeController extends Controller
{
    public function __construct(private BonCommandeService $service) {}

    public function index(Request $request)
    {
        $bons = $this->service->paginate($request->only(['search', 'statut', 'fournisseur_id']));
        return view('logistique.bons-commande.index', compact('bons'));
    }

    public function create()
    {
        $this->authorize('create', BonCommande::class);
        return view('logistique.bons-commande.create');
    }

    public function store(StoreBonCommandeRequest $request)
    {
        $bc = $this->service->creer($request->validated(), $request->input('articles', []), auth()->id());
        return redirect()->route('logistique.bons-commande.show', $bc)->with('success', 'Bon de commande créé.');
    }

    public function show(BonCommande $bonCommande)
    {
        $this->authorize('view', $bonCommande);
        $bonCommande = $this->service->avecDetails($bonCommande);
        return view('logistique.bons-commande.show', compact('bonCommande'));
    }

    public function envoyer(BonCommande $bonCommande)
    {
        $this->authorize('envoyer', $bonCommande);
        $this->service->envoyer($bonCommande);
        return back()->with('success', 'BC envoyé au fournisseur.');
    }

    public function valider(ValiderBonCommandeRequest $request, BonCommande $bonCommande)
    {
        $this->service->validerDirection($bonCommande, auth()->id());
        return back()->with('success', 'BC validé par la Direction.');
    }

    public function annuler(BonCommande $bonCommande)
    {
        $this->authorize('annuler', $bonCommande);
        $this->service->annuler($bonCommande);
        return back()->with('success', 'BC annulé.');
    }
}