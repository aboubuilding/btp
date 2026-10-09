<?php
namespace App\Http\Controllers\Approvisionnement;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Approvisionnement\{
    StoreDemandeAchatRequest,
    ValiderDemandeAchatRequest,
    RejeterDemandeAchatRequest
};
use App\Domain\Approvisionnement\Models\DemandeAchat;
use App\Domain\Approvisionnement\Services\DemandeAchatService;
use Illuminate\Http\Request;

class DemandeAchatController extends Controller
{
    use HandlesModals;

    public function __construct(private DemandeAchatService $service) {}

    public function index(Request $request)
    {
        $demandes = $this->service->paginate($request->only(['statut', 'projet_id']));
        return view('logistique.demandes-achat.index', compact('demandes'));
    }

    public function create()
    {
        $this->authorize('create', DemandeAchat::class);
        return view('logistique.demandes-achat.create');
    }

    public function store(StoreDemandeAchatRequest $request)
    {
        $demande = $this->service->creer($request->validated(), $request->input('articles', []), auth()->id());
        return redirect()->route('logistique.demandes-achat.show', $demande)->with('success', 'Demande créée.');
    }

    public function show(DemandeAchat $demande)
    {
        $this->authorize('view', $demande);
        $demande = $this->service->avecArticles($demande);
        return view('logistique.demandes-achat.show', compact('demande'));
    }

    public function valider(ValiderDemandeAchatRequest $request, DemandeAchat $demande)
    {
        $this->service->valider($demande, auth()->id());
        return back()->with('success', 'Demande validée.');
    }

    public function rejeter(RejeterDemandeAchatRequest $request, DemandeAchat $demande)
    {
        $this->service->rejeter($demande, auth()->id());
        return back()->with('success', 'Demande rejetée.');
    }
}