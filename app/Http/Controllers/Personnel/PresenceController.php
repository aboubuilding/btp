<?php
namespace App\Http\Controllers\Personnel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Paie\{StorePresenceRequest, ValiderPresenceRequest};
use App\Domain\Personnel\Models\Presence;
use App\Domain\Personnel\Services\PresenceService;
use App\Domain\Execution\Models\{EquipeProjet, Projet};
use Illuminate\Http\Request;

class PresenceController extends Controller
{
    public function __construct(private PresenceService $service) {}

    public function index(Request $request)
    {
        $pointages = $this->service->paginate($request->only(['projet_id', 'employee_id', 'statut', 'date']));
        return view('rh.pointages.index', compact('pointages'));
    }

    public function create(Projet $projet)
    {
        $this->authorize('pointer', $projet);
        $equipe = EquipeProjet::with('employe.poste')
            ->where('projet_id', $projet->id)
            ->where('etat', 1)
            ->get();
        return view('rh.pointages.saisie', compact('projet', 'equipe'));
    }

    public function store(StorePresenceRequest $request)
    {
        $this->service->pointer(
            $request->projet_id,
            $request->date,
            $request->input('lignes'),
            $request->user()->id
        );
        return redirect()->route('projets.show', $request->projet_id)->with('success', 'Pointages enregistrés.');
    }

    public function valider(ValiderPresenceRequest $request)
    {
        $count = $this->service->valider($request->projet_id, $request->date, auth()->id());
        return back()->with('success', "{$count} pointages validés.");
    }
}