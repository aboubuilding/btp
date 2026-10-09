<?php
namespace App\Http\Controllers\SousTraitance;

use App\Http\Controllers\Controller;
use App\Http\Requests\SousTraitance\StoreContratSousTraitantRequest;
use App\Domain\SousTraitance\Models\ContratSousTraitant;
use App\Domain\SousTraitance\Services\ContratSousTraitantService;
use App\Domain\Socle\Exceptions\RegleGestionException;
use Illuminate\Http\Request;

class ContratSousTraitantController extends Controller
{
    public function __construct(private ContratSousTraitantService $service) {}

    public function index(Request $request)
    {
        $contrats = $this->service->paginate($request->only(['statut', 'projet_id', 'sous_traitant_id']));
        return view('soustraitance.contrats.index', compact('contrats'));
    }

    public function create()
    {
        $this->authorize('create', ContratSousTraitant::class);
        return view('soustraitance.contrats.create');
    }

    public function store(StoreContratSousTraitantRequest $request)
    {
        try {
            $contrat = $this->service->creer($request->validated());
            return redirect()->route('soustraitance.contrats.show', $contrat)->with('success', 'Contrat créé.');
        } catch (RegleGestionException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(ContratSousTraitant $contrat)
    {
        $this->authorize('view', $contrat);
        $contrat = $this->service->avecDetails($contrat);
        return view('soustraitance.contrats.show', compact('contrat'));
    }

    public function resilier(ContratSousTraitant $contrat)
    {
        $this->authorize('resilier', $contrat);
        $this->service->resilier($contrat);
        return back()->with('success', 'Contrat résilié.');
    }
}