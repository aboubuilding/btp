<?php

namespace App\Http\Controllers;

use App\Http\Requests\EvaluationSousTraitantRequest;
use App\Services\EvaluationSousTraitantService;
use Illuminate\Http\Request;

class EvaluationSousTraitantController extends Controller
{
    protected EvaluationSousTraitantService $service;

    public function __construct(EvaluationSousTraitantService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $evaluations = $this->service->getAll();
        $stats = $this->service->getStats();
        $soustraitants = $this->service->getSoustraitants();
        $projets = $this->service->getProjets();

        return view('admin.evaluations-sous-traitants.index', compact(
            'evaluations',
            'stats',
            'soustraitants',
            'projets'
        ));
    }

    public function store(EvaluationSousTraitantRequest $request)
    {
        $evaluation = $this->service->create($request->validated());

        if ($evaluation) {
            return response()->json([
                'success' => true,
                'message' => 'Évaluation créée avec succès.',
                'data' => $evaluation
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la création de l\'évaluation.'
        ], 500);
    }

    public function update(EvaluationSousTraitantRequest $request, int $id)
    {
        $updated = $this->service->update($id, $request->validated());

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Évaluation mise à jour avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la mise à jour de l\'évaluation.'
        ], 500);
    }

    public function destroy(int $id)
    {
        $deleted = $this->service->delete($id);

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Évaluation supprimée avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la suppression de l\'évaluation.'
        ], 500);
    }

    public function restore(int $id)
    {
        $restored = $this->service->restore($id);

        if ($restored) {
            return response()->json([
                'success' => true,
                'message' => 'Évaluation restaurée avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la restauration de l\'évaluation.'
        ], 500);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('q', '');
        $evaluations = $this->service->search($keyword);

        return response()->json([
            'success' => true,
            'data' => $evaluations
        ]);
    }

    public function getBySoustraitant(int $soustraitantId)
    {
        $data = $this->service->getEvaluationsBySoustraitant($soustraitantId);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getLast()
    {
        $evaluations = $this->service->getLastEvaluations(10);

        return response()->json([
            'success' => true,
            'data' => $evaluations
        ]);
    }
}
