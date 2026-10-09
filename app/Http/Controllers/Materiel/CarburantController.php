<?php
namespace App\Http\Controllers\Materiel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Materiel\StoreReleveCarburantRequest;
use App\Domain\ParcMateriel\Models\{Equipement, ReleveCarburant};
use App\Domain\ParcMateriel\Services\CarburantService;
use App\Domain\Socle\Exceptions\RegleGestionException;

class CarburantController extends Controller
{
    use HandlesModals;

    public function __construct(private CarburantService $service) {}

    public function create(Equipement $equipement)
    {
        $this->authorize('create', ReleveCarburant::class);
        return view('materiel.carburant.partials._form', [
            'equipement' => $equipement,
            'releve'     => new ReleveCarburant(),
        ]);
    }

    public function store(StoreReleveCarburantRequest $request, Equipement $equipement)
    {
        try {
            $this->service->enregistrer($equipement, $request->validated());
            return $this->modalSuccess('Relevé enregistré.');
        } catch (RegleGestionException $e) {
            return $this->modalError($e->getMessage());
        }
    }

    public function edit(ReleveCarburant $releve)
    {
        $this->authorize('update', $releve);
        return view('materiel.carburant.partials._form', [
            'equipement' => $releve->equipement,
            'releve'     => $releve,
        ]);
    }

    public function update(StoreReleveCarburantRequest $request, ReleveCarburant $releve)
    {
        $releve->update($request->validated());
        return $this->modalSuccess('Relevé mis à jour.');
    }
}