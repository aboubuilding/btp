<?php
namespace App\Http\Controllers\Communication;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Socle\StoreCommunicationRequest;
use App\Domain\Socle\Models\Communication;
use App\Domain\Socle\Services\CommunicationService;
use Illuminate\Http\Request;

class CommunicationController extends Controller
{
    use HandlesModals;

    public function __construct(private CommunicationService $service) {}

    public function index(Request $request)
    {
        $communications = Communication::with(['expediteur', 'communicable'])
            ->when($request->type, fn($q, $v) => $q->where('type', $v))
            ->when($request->statut, fn($q, $v) => $q->where('statut', $v))
            ->when($request->search, fn($q, $v) => $q->where('sujet', 'like', "%{$v}%"))
            ->latest()
            ->paginate(25);

        $stats = $this->service->statistiques();
        return view('communication.index', compact('communications', 'stats'));
    }

    public function create(Request $request)
    {
        $communicable = null;
        if ($request->filled('type') && $request->filled('id')) {
            $class = decrypt($request->type);
            $communicable = $class::find($request->id);
        }
        return view('communication.partials._form', [
            'communication' => new Communication(),
            'communicable'  => $communicable,
        ]);
    }

    public function store(StoreCommunicationRequest $request)
    {
        $data = $request->validated();
        $communication = Communication::create([
            'sujet'             => $data['sujet'],
            'corps'             => $data['corps'],
            'type'              => 'email',
            'statut'            => 'brouillon',
            'destinataires'     => $data['destinataires'],
            'communicable_type' => $data['communicable_type'] ?? null,
            'communicable_id'   => $data['communicable_id'] ?? null,
            'expediteur_id'     => auth()->id(),
            'planifie_le'       => $data['planifie_le'] ?? null,
        ]);

        if ($request->hasFile('pieces_jointes')) {
            $pj = [];
            foreach ($request->file('pieces_jointes') as $file) {
                $pj[] = ['chemin' => $file->store('communications', 'local'), 'nom' => $file->getClientOriginalName()];
            }
            $communication->update(['pieces_jointes' => $pj]);
        }

        if (!empty($data['envoyer_maintenant'])) {
            $ok = $this->service->envoyerEmail($communication);
            return $ok
                ? $this->modalSuccess('Email envoyé.')
                : $this->modalError('Échec : ' . $communication->fresh()->erreur);
        }

        return $this->modalSuccess('Communication enregistrée.');
    }

    public function show(Communication $communication)
    {
        $communication->load(['expediteur', 'communicable']);
        return view('communication.show', compact('communication'));
    }

    public function envoyer(Communication $communication)
    {
        $ok = $this->service->envoyerEmail($communication);
        return $ok
            ? back()->with('success', 'Email envoyé.')
            : back()->with('error', 'Échec : ' . $communication->fresh()->erreur);
    }

    public function destroy(Communication $communication)
    {
        $communication->update(['etat' => 0]);
        return $this->modalSuccess('Communication archivée.');
    }
}