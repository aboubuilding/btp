<?php
namespace App\Http\Controllers\Finances;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finances\StorePlanComptableRequest;
use App\Domain\Finances\Models\PlanComptable;
use App\Domain\Finances\Services\PlanComptableService;
use Illuminate\Http\Request;

class PlanComptableController extends Controller
{
    public function __construct(private PlanComptableService $service) {}

    public function index(Request $request)
    {
        $comptes = PlanComptable::where('etat', 1)
            ->when($request->classe, fn($q, $v) => $q->where('classe', $v))
            ->orderBy('numero')
            ->paginate(50);
        return view('finances.plan-comptable.index', compact('comptes'));
    }

    public function create()
    {
        $this->authorize('create', PlanComptable::class);
        return view('finances.plan-comptable.create');
    }

    public function store(StorePlanComptableRequest $request)
    {
        $this->service->creer($request->validated());
        return redirect()->route('finances.plan-comptable.index')->with('success', 'Compte créé.');
    }

    public function edit(PlanComptable $planComptable)
    {
        $this->authorize('update', $planComptable);
        return view('finances.plan-comptable.edit', ['compte' => $planComptable]);
    }

    public function update(StorePlanComptableRequest $request, PlanComptable $planComptable)
    {
        $this->service->mettreAJour($planComptable, $request->validated());
        return back()->with('success', 'Compte mis à jour.');
    }
}