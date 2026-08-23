<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaiementSousTraitantRequest;
use App\Services\PaiementSousTraitantService;
use Illuminate\Http\Request;

class PaiementSousTraitantController extends Controller
{
    protected PaiementSousTraitantService $service;

    public function __construct(PaiementSousTraitantService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $paiements = $this->service->getAll();
        $stats = $this->service->getStats();
        $factures = $this->service->getFactures();
        $modes = $this->service->getModes();

        return view('admin.paiements-sous-traitants.index', compact(
            'paiements',
            'stats',
            'factures',
            'modes'
        ));
    }

    public function store(PaiementSousTraitantRequest $request)
    {
        $paiement = $this->service->create($request->validated());

        if ($paiement) {
            return response()->json([
                'success' => true,
                'message' => 'Paiement enregistré avec succès.',
                'data' => $paiement
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de l\'enregistrement du paiement.'
        ], 500);
    }

    public function update(PaiementSousTraitantRequest $request, int $id)
    {
        $updated = $this->service->update($id, $request->validated());

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Paiement mis à jour avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la mise à jour du paiement.'
        ], 500);
    }

    public function destroy(int $id)
    {
        $deleted = $this->service->delete($id);

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Paiement supprimé avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la suppression du paiement.'
        ], 500);
    }

    public function restore(int $id)
    {
        $restored = $this->service->restore($id);

        if ($restored) {
            return response()->json([
                'success' => true,
                'message' => 'Paiement restauré avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la restauration du paiement.'
        ], 500);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('q', '');
        $paiements = $this->service->search($keyword);

        return response()->json([
            'success' => true,
            'data' => $paiements
        ]);
    }

    public function getByFacture(int $factureId)
    {
        $paiements = $this->service->getPaiementsByFacture($factureId);
        $total = $this->service->getTotalPaiementsByFacture($factureId);

        return response()->json([
            'success' => true,
            'data' => [
                'paiements' => $paiements,
                'total' => $total
            ]
        ]);
    }
}
