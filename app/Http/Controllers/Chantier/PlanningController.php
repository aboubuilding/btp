<?php
namespace App\Http\Controllers\Chantier;

use App\Http\Controllers\Controller;
use App\Domain\Execution\Models\{Projet, Tache};
use App\Domain\Execution\Services\PlanningService;
use Illuminate\Http\{JsonResponse, Request};

class PlanningController extends Controller
{
    public function __construct(private PlanningService $service) {}

    public function gantt(Projet $projet)
    {
        $this->authorize('view', $projet);
        return view('chantiers.planning.gantt', compact('projet'));
    }

    public function data(Projet $projet): JsonResponse
    {
        $this->authorize('view', $projet);
        return response()->json($this->service->dataPourGantt($projet));
    }

    public function updateTache(Request $request, Tache $tache): JsonResponse
    {
        $this->authorize('update', $tache);

        $data = $request->validate([
            'start'    => ['required', 'date'],
            'end'      => ['required', 'date', 'after_or_equal:start'],
            'progress' => ['nullable', 'integer', 'between:0,100'],
        ]);

        $tache->update([
            'date_debut'             => $data['start'],
            'date_fin'               => $data['end'],
            'pourcentage_avancement' => $data['progress'] ?? $tache->pourcentage_avancement,
        ]);

        return response()->json(['success' => true, 'message' => 'Tâche mise à jour.']);
    }
}