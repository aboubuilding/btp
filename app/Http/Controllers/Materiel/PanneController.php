<?php
namespace App\Http\Controllers\Materiel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Materiel\{StorePanneRequest, CloturerPanneRequest};
use App\Domain\ParcMateriel\Models\{Equipement, PanneEquipement};
use App\Domain\ParcMateriel\Services\PanneService;

class PanneController extends Controller
{
    use HandlesModals;

    public function __construct(private PanneService $service) {}

    public function create(Equipement $equipement)
    {
        $this->authorize('create', PanneEquipement::class);
        return view('materiel.pannes.partials._form', [
            'equipement' => $equipement,
            'panne'      => new PanneEquipement(),
        ]);
    }

    public function store(StorePanneRequest $request, Equipement $equipement)
    {
        $this->service->declarer($equipement, $request->validated(), auth()->id());
        return $this->modalSuccess('Panne déclarée.');
    }

    public function edit(PanneEquipement $panne)
    {
        $this->authorize('update', $panne);
        return view('materiel.pannes.partials._form', [
            'equipement' => $panne->equipement,
            'panne'      => $panne,
        ]);
    }

    public function update(StorePanneRequest $request, PanneEquipement $panne)
    {
        $panne->update($request->validated());
        return $this->modalSuccess('Panne mise à jour.');
    }

    public function cloturer(CloturerPanneRequest $request, PanneEquipement $panne)
    {
        $this->authorize('cloturer', $panne);
        $this->service->cloturer($panne, $request->input('cout_reparation'));
        return $this->modalSuccess('Panne clôturée.');
    }
}