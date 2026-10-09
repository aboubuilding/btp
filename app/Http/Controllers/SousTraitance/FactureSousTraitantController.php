<?php
namespace App\Http\Controllers\SousTraitance;

use App\Http\Controllers\Controller;
use App\Http\Requests\SousTraitance\StoreFactureSousTraitantRequest;
use App\Domain\SousTraitance\Models\FactureSousTraitant;
use App\Domain\SousTraitance\Services\FactureSousTraitantService;
use Illuminate\Http\Request;

class FactureSousTraitantController extends Controller
{
    public function __construct(private FactureSousTraitantService $service) {}

    public function index(Request $request)
    {
        $factures = FactureSousTraitant::with('contrat.soustraitant')
            ->when($request->statut, fn($q, $v) => $q->where('statut', $v))
            ->latest()
            ->paginate(25);
        return view('soustraitance.factures.index', compact('factures'));
    }

    public function create()
    {
        $this->authorize('create', FactureSousTraitant::class);
        return view('soustraitance.factures.create');
    }

    public function store(StoreFactureSousTraitantRequest $request)
    {
        $facture = $this->service->creer($request->validated());
        return redirect()->route('soustraitance.factures.index')->with('success', 'Facture enregistrée.');
    }

    public function show(FactureSousTraitant $facture)
    {
        $this->authorize('view', $facture);
        $facture->load(['contrat.soustraitant', 'paiements']);
        return view('soustraitance.factures.show', compact('facture'));
    }
}