<?php
namespace App\Http\Controllers\Chantier;

use App\Http\Controllers\Controller;
use App\Http\Requests\Execution\StoreAvancementRequest;
use App\Domain\Execution\Models\{AvancementProjet, Projet};
use App\Domain\Execution\Services\AvancementService;

class AvancementController extends Controller
{
    public function __construct(private AvancementService $service) {}

    public function index()
    {
        $rapports = AvancementProjet::with(['projet', 'saisiPar', 'validePar'])
            ->when(request('projet_id'), fn($q, $v) => $q->where('projet_id', $v))
            ->latest('date_rapport')
            ->paginate(50);
        return view('chantiers.journal.index', compact('rapports'));
    }

    public function create(Projet $projet)
    {
        $this->authorize('create', AvancementProjet::class);
        return view('chantiers.journal.create', compact('projet'));
    }

    public function store(StoreAvancementRequest $request, Projet $projet)
    {
        $this->service->enregistrer($projet, $request->validated(), auth()->id());
        return redirect()->route('projets.show', $projet)->with('success', 'Rapport d\'avancement enregistré.');
    }

    public function valider(AvancementProjet $avancement)
    {
        $this->authorize('valider', $avancement);
        $this->service->valider($avancement, auth()->id());
        return back()->with('success', 'Rapport validé.');
    }
}