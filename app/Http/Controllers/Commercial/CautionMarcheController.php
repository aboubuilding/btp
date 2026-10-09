<?php
namespace App\Http\Controllers\Commercial;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Commercial\StoreCautionRequest;
use App\Domain\Commercial\Models\{CautionMarche, Marche};
use App\Domain\Commercial\Services\CautionService;

class CautionMarcheController extends Controller
{
    use HandlesModals;

    public function __construct(private CautionService $service) {}

    public function create(Marche $marche)
    {
        $this->authorize('create', CautionMarche::class);
        return view('commercial.cautions.partials._form', [
            'marche'  => $marche,
            'caution' => new CautionMarche(),
        ]);
    }

    public function store(StoreCautionRequest $request, Marche $marche)
    {
        $caution = $this->service->creer(array_merge($request->validated(), ['marche_id' => $marche->id]));
        return $this->modalSuccess("Caution de {$caution->montant} FCFA enregistrée.");
    }

    public function edit(CautionMarche $caution)
    {
        $this->authorize('update', $caution);
        return view('commercial.cautions.partials._form', [
            'marche'  => $caution->marche,
            'caution' => $caution,
        ]);
    }

    public function update(StoreCautionRequest $request, CautionMarche $caution)
    {
        $this->service->mettreAJour($caution, $request->validated());
        return $this->modalSuccess('Caution mise à jour.');
    }

    public function destroy(CautionMarche $caution)
    {
        $this->authorize('delete', $caution);
        $this->service->supprimer($caution);
        return $this->modalSuccess('Caution supprimée.');
    }
}