<?php
namespace App\Http\Controllers\Materiel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Materiel\StoreMaintenanceRequest;
use App\Domain\ParcMateriel\Models\{MaintenanceEquipement, Equipement};
use App\Domain\ParcMateriel\Services\MaintenanceService;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function __construct(private MaintenanceService $service) {}

    public function index(Request $request)
    {
        $maintenances = $this->service->paginate($request->only(['type', 'equipement_id']));
        return view('materiel.maintenances.index', compact('maintenances'));
    }

    public function create()
    {
        $this->authorize('create', MaintenanceEquipement::class);
        return view('materiel.maintenances.create');
    }

    public function store(StoreMaintenanceRequest $request)
    {
        $equipement = Equipement::findOrFail($request->equipement_id);
        $this->service->enregistrer($equipement, $request->validated());
        return redirect()->route('materiel.maintenances.index')->with('success', 'Maintenance enregistrée.');
    }

    public function edit(MaintenanceEquipement $maintenance)
    {
        $this->authorize('update', $maintenance);
        return view('materiel.maintenances.edit', compact('maintenance'));
    }

    public function update(StoreMaintenanceRequest $request, MaintenanceEquipement $maintenance)
    {
        $maintenance->update($request->validated());
        return back()->with('success', 'Maintenance mise à jour.');
    }
}