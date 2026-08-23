<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlanComptableRequest;
use App\Services\PlanComptableService;
use Illuminate\Http\Request;

class PlanComptableController extends Controller
{
    protected PlanComptableService $service;

    public function __construct(PlanComptableService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $comptes = $this->service->getAll();
        $flatList = $this->service->getTreeFlat();
        $stats = $this->service->getStats();
        $types = $this->service->getTypes();
        $parents = $this->service->getParents();

        return view('admin.plan-comptable.index', compact(
            'comptes',
            'flatList',
            'stats',
            'types',
            'parents'
        ));
    }

    public function store(PlanComptableRequest $request)
    {
        $compte = $this->service->create($request->validated());

        if ($compte) {
            return response()->json([
                'success' => true,
                'message' => 'Compte créé avec succès.',
                'data' => $compte
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la création du compte.'
        ], 500);
    }

    public function update(PlanComptableRequest $request, int $id)
    {
        $updated = $this->service->update($id, $request->validated());

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Compte mis à jour avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la mise à jour du compte.'
        ], 500);
    }

    public function reorder(Request $request, int $id)
    {
        $request->validate([
            'parent_id' => ['nullable', 'exists:plan_comptables,id'],
        ]);

        $reordered = $this->service->reorder($id, $request->input('parent_id'));

        if ($reordered) {
            return response()->json([
                'success' => true,
                'message' => 'Compte réorganisé avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Impossible de réorganiser le compte (vérifiez les dépendances).'
        ], 500);
    }

    public function destroy(int $id)
    {
        $deleted = $this->service->delete($id);

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Compte supprimé avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Impossible de supprimer ce compte (il a des enfants).'
        ], 500);
    }

    public function restore(int $id)
    {
        $restored = $this->service->restore($id);

        if ($restored) {
            return response()->json([
                'success' => true,
                'message' => 'Compte restauré avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la restauration du compte.'
        ], 500);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('q', '');
        $comptes = $this->service->search($keyword);

        return response()->json([
            'success' => true,
            'data' => $comptes
        ]);
    }

    public function getByType(string $type)
    {
        $comptes = $this->service->getByType($type);

        return response()->json([
            'success' => true,
            'data' => $comptes
        ]);
    }

    public function getParents(Request $request)
    {
        $excludeId = $request->input('exclude_id');
        $parents = $this->service->getParents($excludeId);

        return response()->json([
            'success' => true,
            'data' => $parents
        ]);
    }

    public function getTree()
    {
        $tree = $this->service->getAll();

        return response()->json([
            'success' => true,
            'data' => $tree
        ]);
    }
}
