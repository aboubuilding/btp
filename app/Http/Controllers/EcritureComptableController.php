<?php

namespace App\Http\Controllers;

use App\Http\Requests\EcritureComptableRequest;
use App\Services\EcritureComptableService;
use Illuminate\Http\Request;

class EcritureComptableController extends Controller
{
    protected EcritureComptableService $service;

    public function __construct(EcritureComptableService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $ecritures = $this->service->getAll();
        $stats = $this->service->getStats();
        $exercices = $this->service->getExercices();
        $comptes = $this->service->getComptes();
        $statuts = $this->service->getStatuts();
        $typesReference = $this->service->getTypesReference();

        return view('admin.ecritures-comptables.index', compact(
            'ecritures',
            'stats',
            'exercices',
            'comptes',
            'statuts',
            'typesReference'
        ));
    }

    public function store(EcritureComptableRequest $request)
    {
        $ecriture = $this->service->create($request->validated());

        if ($ecriture) {
            return response()->json([
                'success' => true,
                'message' => 'Écriture créée avec succès.',
                'data' => $ecriture
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la création de l\'écriture.'
        ], 500);
    }

    public function update(EcritureComptableRequest $request, int $id)
    {
        $updated = $this->service->update($id, $request->validated());

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Écriture mise à jour avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la mise à jour de l\'écriture.'
        ], 500);
    }

    public function valider(int $id)
    {
        $validee = $this->service->valider($id);

        if ($validee) {
            return response()->json([
                'success' => true,
                'message' => 'Écriture validée avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Impossible de valider l\'écriture. Vérifiez qu\'elle est équilibrée.'
        ], 500);
    }

    public function contrePasser(int $id)
    {
        $nouvelleEcriture = $this->service->contrePasser($id);

        if ($nouvelleEcriture) {
            return response()->json([
                'success' => true,
                'message' => 'Contre-passation effectuée avec succès.',
                'data' => $nouvelleEcriture
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Impossible de contre-passer l\'écriture.'
        ], 500);
    }

    public function destroy(int $id)
    {
        $deleted = $this->service->delete($id);

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Écriture supprimée avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Impossible de supprimer cette écriture (elle est peut-être validée).'
        ], 500);
    }

    public function restore(int $id)
    {
        $restored = $this->service->restore($id);

        if ($restored) {
            return response()->json([
                'success' => true,
                'message' => 'Écriture restaurée avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la restauration de l\'écriture.'
        ], 500);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('q', '');
        $ecritures = $this->service->search($keyword);

        return response()->json([
            'success' => true,
            'data' => $ecritures
        ]);
    }

    public function getLignes(int $id)
    {
        $lignes = $this->service->getLignesByEcriture($id);
        $ecriture = $this->service->getEcriture($id);

        return response()->json([
            'success' => true,
            'data' => [
                'ecriture' => $ecriture,
                'lignes' => $lignes
            ]
        ]);
    }

    public function getGrandLivre(Request $request)
    {
        $exerciceId = $request->input('exercice_id');
        $compteId = $request->input('compte_id');

        $data = $this->service->getGrandLivre($exerciceId, $compteId);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function generateNumero()
    {
        $numero = $this->service->generateNumero();

        return response()->json([
            'success' => true,
            'numero' => $numero
        ]);
    }
}
