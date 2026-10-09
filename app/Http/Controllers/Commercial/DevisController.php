<?php
namespace App\Http\Controllers\Commercial;

use App\Http\Controllers\Controller;
use App\Http\Requests\Commercial\{StoreDevisRequest, UpdateDevisRequest};
use App\Domain\Commercial\Models\Devis;
use App\Domain\Commercial\Services\DevisService;
use Illuminate\Http\Request;

class DevisController extends Controller
{
    public function __construct(private DevisService $service) {}

    public function index(Request $request)
    {
        $devis = $this->service->paginate($request->only(['search', 'statut', 'client_id']));
        return view('commercial.devis.index', compact('devis'));
    }

    public function create()
    {
        $this->authorize('create', Devis::class);
        return view('commercial.devis.create');
    }

    public function store(StoreDevisRequest $request)
    {
        $devis = $this->service->creer($request->validated(), $request->input('lignes', []));
        return redirect()->route('commercial.devis.show', $devis)->with('success', 'Devis créé.');
    }

    public function show(Devis $devis)
    {
        $this->authorize('view', $devis);
        $devis = $this->service->avecLignes($devis);
        return view('commercial.devis.show', compact('devis'));
    }

    public function edit(Devis $devis)
    {
        $this->authorize('update', $devis);
        $devis->load('lignes');
        return view('commercial.devis.edit', compact('devis'));
    }

    public function update(UpdateDevisRequest $request, Devis $devis)
    {
        $this->service->mettreAJour($devis, $request->validated(), $request->input('lignes', []));
        return redirect()->route('commercial.devis.show', $devis)->with('success', 'Devis mis à jour.');
    }

    public function envoyer(Devis $devis)
    {
        $this->authorize('envoyer', $devis);
        $this->service->envoyer($devis);
        return back()->with('success', 'Devis envoyé au client.');
    }

    public function accepter(Devis $devis)
    {
        $this->authorize('accepter', $devis);
        $this->service->accepter($devis);
        return back()->with('success', 'Devis accepté.');
    }

    public function refuser(Request $request, Devis $devis)
    {
        $this->authorize('refuser', $devis);
        $this->service->refuser($devis, $request->input('motif'));
        return back()->with('success', 'Devis refusé.');
    }

    public function dupliquer(Devis $devis)
    {
        $this->authorize('dupliquer', $devis);
        $nouveau = $this->service->dupliquer($devis);
        return redirect()->route('commercial.devis.edit', $nouveau)->with('success', 'Devis dupliqué.');
    }

    public function destroy(Devis $devis)
    {
        $this->authorize('delete', $devis);
        $this->service->supprimer($devis);
        return redirect()->route('commercial.devis.index')->with('success', 'Devis supprimé.');
    }
}