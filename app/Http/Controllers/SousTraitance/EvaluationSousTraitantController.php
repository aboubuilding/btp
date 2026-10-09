<?php
namespace App\Http\Controllers\SousTraitance;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\SousTraitance\StoreEvaluationSousTraitantRequest;
use App\Domain\SousTraitance\Models\{EvaluationSousTraitant, Soustraitant};
use App\Domain\SousTraitance\Services\EvaluationService;

class EvaluationSousTraitantController extends Controller
{
    use HandlesModals;

    public function __construct(private EvaluationService $service) {}

    public function create(Soustraitant $soustraitant)
    {
        $this->authorize('create', EvaluationSousTraitant::class);
        return view('soustraitance.evaluations.partials._form', [
            'soustraitant' => $soustraitant,
            'evaluation'   => new EvaluationSousTraitant(),
        ]);
    }

    public function store(StoreEvaluationSousTraitantRequest $request, Soustraitant $soustraitant)
    {
        $this->service->creer($soustraitant, $request->validated(), auth()->id());
        return $this->modalSuccess('Évaluation enregistrée.');
    }
}