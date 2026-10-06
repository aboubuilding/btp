<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExerciceFiscalRequest;
use App\Services\ExerciceFiscalService;
use Illuminate\Http\Request;

class ExerciceFiscalController extends Controller
{
    protected ExerciceFiscalService $service;

    public function __construct(ExerciceFiscalService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $exercices = $this->service->getAll();
        $stats = $this->service->getStats();
        $statuts = $this->service->getStatuts();
        $currentExercice = $this->service->getCurrentExercice();
        $lastExercice = $this->service->getLastExercice();

        return view('admin.exercices-fiscaux.index', compact(
            'exercices',
            'stats',
            'statuts',
            'currentExercice',
            'lastExercice'
        ));
    }

    public function store(ExerciceFiscalRequest $request)
    {
        $exercice = $this->service->create($request->validated());

        if ($exercice) {
            return response()->json([
                'success' => true,
                'message' => 'Exercice créé avec succès.',
                'data' => $exercice
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la création de l\'exercice.'
        ], 500);
    }

    public function update(ExerciceFiscalRequest $request, int $id)
    {
        $updated = $this->service->update($id, $request->validated());

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Exercice mis à jour avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la mise à jour de l\'exercice.'
        ], 500);
    }

    public function close(int $id)
    {
        $closed = $this->service->close($id);

        if ($closed) {
            return response()->json([
                'success' => true,
                'message' => 'Exercice clôturé avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Impossible de clôturer cet exercice.'
        ], 500);
    }

    public function open(int $id)
    {
        $opened = $this->service->open($id);

        if ($opened) {
            return response()->json([
                'success' => true,
                'message' => 'Exercice ouvert avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Impossible d\'ouvrir cet exercice.'
        ], 500);
    }

    public function destroy(int $id)
    {
        $deleted = $this->service->delete($id);

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Exercice supprimé avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Impossible de supprimer cet exercice (il contient peut-être des écritures).'
        ], 500);
    }

    public function restore(int $id)
    {
        $restored = $this->service->restore($id);

        if ($restored) {
            return response()->json([
                'success' => true,
                'message' => 'Exercice restauré avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la restauration de l\'exercice.'
        ], 500);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('q', '');
        $exercices = $this->service->search($keyword);

        return response()->json([
            'success' => true,
            'data' => $exercices
        ]);
    }

    public function getCurrent()
    {
        $exercice = $this->service->getCurrentExercice();

        return response()->json([
            'success' => true,
            'data' => $exercice
        ]);
    }

    public function getStats()
    {
        $stats = $this->service->getStats();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
