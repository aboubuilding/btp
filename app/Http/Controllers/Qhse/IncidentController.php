<?php
namespace App\Http\Controllers\Qhse;

use App\Http\Controllers\Controller;
use App\Http\Requests\Qhse\{StoreIncidentRequest, UpdateIncidentRequest, CloturerIncidentRequest};
use App\Domain\QHSE\Models\IncidentSecurite;
use App\Domain\QHSE\Services\IncidentService;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function __construct(private IncidentService $service) {}

    public function index(Request $request)
    {
        $incidents = $this->service->paginate($request->only(['projet_id', 'gravite', 'statut', 'type']));
        $stats = $this->service->statistiques();
        return view('qhse.incidents.index', compact('incidents', 'stats'));
    }

    public function create()
    {
        $this->authorize('create', IncidentSecurite::class);
        return view('qhse.incidents.create');
    }

    public function store(StoreIncidentRequest $request)
    {
        $this->service->declarer($request->validated(), auth()->id());
        return redirect()->route('qhse.incidents.index')->with('success', 'Incident déclaré.');
    }

    public function show(IncidentSecurite $incident)
    {
        $this->authorize('view', $incident);
        $incident = $this->service->avecDetails($incident);
        return view('qhse.incidents.show', compact('incident'));
    }

    public function edit(IncidentSecurite $incident)
    {
        $this->authorize('update', $incident);
        return view('qhse.incidents.edit', compact('incident'));
    }

    public function update(UpdateIncidentRequest $request, IncidentSecurite $incident)
    {
        $this->service->mettreAJour($incident, $request->validated());
        return redirect()->route('qhse.incidents.show', $incident)->with('success', 'Incident mis à jour.');
    }

    public function cloturer(CloturerIncidentRequest $request, IncidentSecurite $incident)
    {
        $this->authorize('cloturer', $incident);
        $this->service->cloturer($incident, $request->actions_correctives);
        return back()->with('success', 'Incident clos.');
    }
}