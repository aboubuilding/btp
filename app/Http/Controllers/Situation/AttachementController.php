<?php
namespace App\Http\Controllers\Situation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Execution\{StoreAttachementRequest, UpdateAttachementRequest};
use App\Domain\Execution\Models\{Attachement, Projet};
use App\Domain\Execution\Services\AttachementService;
use Illuminate\Http\Request;

class AttachementController extends Controller
{
    public function __construct(private AttachementService $service) {}

    public function index(Request $request)
    {
        $attachements = Attachement::with(['projet', 'etabliPar'])
            ->when($request->projet_id, fn($q, $v) => $q->where('projet_id', $v))
            ->latest('periode_debut')
            ->paginate(25);
        return view('situations.attachements.index', compact('attachements'));
    }

    public function create()
    {
        $this->authorize('create', Attachement::class);
        return view('situations.attachements.create');
    }

    public function store(StoreAttachementRequest $request)
    {
        $projet = Projet::findOrFail($request->projet_id);
        $attachement = $this->service->creer($projet, $request->validated(), $request->input('lignes', []), auth()->id());
        return redirect()->route('situations.attachements.show', $attachement)->with('success', 'Attachement créé.');
    }

    public function show(Attachement $attachement)
    {
        $this->authorize('view', $attachement);
        $attachement = $this->service->avecLignes($attachement);
        return view('situations.attachements.show', compact('attachement'));
    }

    public function edit(Attachement $attachement)
    {
        $this->authorize('update', $attachement);
        return view('situations.attachements.edit', compact('attachement'));
    }

    public function update(UpdateAttachementRequest $request, Attachement $attachement)
    {
        $this->service->mettreAJour($attachement, $request->validated(), $request->input('lignes', []));
        return redirect()->route('situations.attachements.show', $attachement)->with('success', 'Attachement mis à jour.');
    }

    public function valider(Attachement $attachement)
    {
        $this->authorize('valider', $attachement);
        $this->service->valider($attachement);
        return back()->with('success', 'Attachement validé.');
    }

    public function destroy(Attachement $attachement)
    {
        $this->authorize('delete', $attachement);
        $this->service->supprimer($attachement);
        return redirect()->route('situations.attachements.index')->with('success', 'Attachement supprimé.');
    }
}