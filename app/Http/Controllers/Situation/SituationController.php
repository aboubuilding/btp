<?php
namespace App\Http\Controllers\Situation;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Execution\StoreSituationRequest;
use App\Domain\Execution\Models\{Attachement, Projet, Situation};
use App\Domain\Execution\Services\SituationService;
use App\Domain\Socle\Exceptions\RegleGestionException;
use App\Domain\Socle\Services\PdfService;
use Illuminate\Http\Request;

class SituationController extends Controller
{
    use HandlesModals;

    public function __construct(private SituationService $service) {}

    public function index(Request $request)
    {
        $situations = $this->service->paginate($request->only(['projet_id', 'statut']));
        return view('situations.situations.index', compact('situations'));
    }

    public function create()
    {
        $this->authorize('create', Situation::class);
        return view('situations.situations.create');
    }

    public function store(StoreSituationRequest $request)
    {
        try {
            $projet = Projet::findOrFail($request->projet_id);
            $attachement = Attachement::findOrFail($request->attachement_id);
            $situation = $this->service->creer($projet, $attachement);
            return redirect()->route('situations.situations.show', $situation)->with('success', 'Situation créée.');
        } catch (RegleGestionException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(Situation $situation)
    {
        $this->authorize('view', $situation);
        $situation = $this->service->avecDetails($situation);
        return view('situations.situations.show', compact('situation'));
    }

    public function valider(Situation $situation)
    {
        $this->authorize('valider', $situation);
        try {
            $this->service->valider($situation, auth()->id());
            return back()->with('success', 'Situation validée.');
        } catch (RegleGestionException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function transmettre(Situation $situation)
    {
        $this->authorize('transmettre', $situation);
        $this->service->transmettre($situation);
        return back()->with('success', 'Situation transmise au maître d\'œuvre.');
    }

    public function approuver(Situation $situation)
    {
        $this->authorize('approuver', $situation);
        $this->service->approuver($situation);
        return back()->with('success', 'Situation approuvée.');
    }

    public function facturer(Situation $situation)
    {
        $this->authorize('facturer', $situation);
        return redirect()->route('finances.factures.create', ['situation_id' => $situation->id, 'type' => 'client']);
    }

    public function pdf(Situation $situation, PdfService $pdf)
    {
        $this->authorize('pdf', $situation);
        $situation = $this->service->avecDetails($situation);
        return $pdf->download('pdf.situation', ['situation' => $situation], "SIT-{$situation->projet->code}-{$situation->numero}");
    }

    public function destroy(Situation $situation)
    {
        $this->authorize('delete', $situation);
        try {
            $this->service->supprimer($situation);
            return redirect()->route('situations.situations.index')->with('success', 'Situation supprimée.');
        } catch (RegleGestionException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}