<?php
namespace App\Http\Controllers\Finances;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finances\StoreEcritureRequest;
use App\Domain\Finances\Models\EcritureComptable;
use App\Domain\Finances\Services\ComptabiliteService;
use App\Domain\Finances\Repositories\EcritureComptableRepositoryInterface;
use App\Domain\Socle\Exceptions\RegleGestionException;
use Illuminate\Http\Request;

class EcritureComptableController extends Controller
{
    public function __construct(
        private ComptabiliteService $service,
        private EcritureComptableRepositoryInterface $repo,
    ) {}

    public function index(Request $request)
    {
        $ecritures = $this->repo->paginateAvecRelations($request->only(['statut', 'exercice_fiscal_id']));
        return view('finances.ecritures.index', compact('ecritures'));
    }

    public function create()
    {
        $this->authorize('create', EcritureComptable::class);
        return view('finances.ecritures.create');
    }

    public function store(StoreEcritureRequest $request)
    {
        try {
            $ecriture = $this->service->enregistrer($request->safe()->except(['lignes']), $request->input('lignes', []));
            return redirect()->route('finances.ecritures.show', $ecriture)->with('success', 'Écriture enregistrée.');
        } catch (RegleGestionException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(EcritureComptable $ecriture)
    {
        $this->authorize('view', $ecriture);
        $ecriture = $this->repo->avecLignes($ecriture);
        return view('finances.ecritures.show', compact('ecriture'));
    }

    public function valider(EcritureComptable $ecriture)
    {
        $this->authorize('valider', $ecriture);
        try {
            $this->service->valider($ecriture, auth()->id());
            return back()->with('success', 'Écriture validée.');
        } catch (RegleGestionException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}