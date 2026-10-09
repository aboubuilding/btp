<?php
namespace App\Http\Controllers\Qhse;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Qhse\{StorePvReceptionRequest, SignerPvReceptionRequest};
use App\Domain\QHSE\Models\PvReception;
use App\Domain\QHSE\Services\PvReceptionService;
use App\Domain\Socle\Exceptions\RegleGestionException;
use Illuminate\Http\Request;

class PvReceptionController extends Controller
{
    use HandlesModals;

    public function __construct(private PvReceptionService $service) {}

    public function index(Request $request)
    {
        $pvs = $this->service->paginate($request->only(['type', 'statut', 'projet_id']));
        return view('qhse.pv-receptions.index', compact('pvs'));
    }

    public function create()
    {
        $this->authorize('create', PvReception::class);
        return view('qhse.pv-receptions.partials._form', ['pv' => new PvReception()]);
    }

    public function store(StorePvReceptionRequest $request)
    {
        $pv = $this->service->creer($request->validated(), $request->file('chemin_fichier'));
        return $this->modalSuccess('PV enregistré.');
    }

    public function show(PvReception $pv)
    {
        $this->authorize('view', $pv);
        $pv = $this->service->avecDetails($pv);
        return view('qhse.pv-receptions.show', compact('pv'));
    }

    public function signer(SignerPvReceptionRequest $request, PvReception $pv)
    {
        try {
            $this->service->signer($pv, auth()->id());
            return back()->with('success', 'PV signé.');
        } catch (RegleGestionException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}