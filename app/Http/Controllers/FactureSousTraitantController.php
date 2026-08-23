<?php

namespace App\Http\Controllers;

use App\Http\Requests\FactureSousTraitantRequest;
use App\Services\FactureSousTraitantService;
use Illuminate\Http\Request;

class FactureSousTraitantController extends Controller
{
    protected FactureSousTraitantService $service;

    public function __construct(FactureSousTraitantService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $factures = $this->service->getAll();
        $stats = $this->service->getStats();
        $soustraitants = $this->service->getSoustraitants();
        $projets = $this->service->getProjets();
        $statuts = $this->service->getStatuts();

        return view('admin.factures-sous-traitants.index', compact(
            'factures',
            'stats',
            'soustraitants',
            'projets',
            'statuts'
        ));
    }

    public function store(FactureSousTraitantRequest $request)
    {
        $data = $request->validated();

        // Générer le numéro de facture si non fourni
        if (empty($data['numero_facture'])) {
            $data['numero_facture'] = $this->service->generateNumeroFacture();
        }

        $facture = $this->service->create($data);

        if ($facture) {
            return response()->json([
                'success' => true,
                'message' => 'Facture ajoutée avec succès.',
                'data' => $facture
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de l\'ajout de la facture.'
        ], 500);
    }

    public function update(FactureSousTraitantRequest $request, int $id)
    {
        $updated = $this->service->update($id, $request->validated());

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Facture mise à jour avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la mise à jour de la facture.'
        ], 500);
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'statut' => ['required', 'string', 'in:emise,payee,partiellement_payee,annulee,en_retard'],
        ]);

        $updated = $this->service->updateStatus($id, $request->input('statut'));

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la mise à jour du statut.'
        ], 500);
    }

    public function marquerPayee(int $id)
    {
        $updated = $this->service->marquerPayee($id);

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Facture marquée comme payée.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors du marquage de la facture.'
        ], 500);
    }

    public function contester(int $id)
    {
        $updated = $this->service->contester($id);

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Facture contestée avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la contestation.'
        ], 500);
    }

    public function destroy(int $id)
    {
        $deleted = $this->service->delete($id);

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Facture supprimée avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la suppression de la facture.'
        ], 500);
    }

    public function restore(int $id)
    {
        $restored = $this->service->restore($id);

        if ($restored) {
            return response()->json([
                'success' => true,
                'message' => 'Facture restaurée avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la restauration de la facture.'
        ], 500);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('q', '');
        $factures = $this->service->search($keyword);

        return response()->json([
            'success' => true,
            'data' => $factures
        ]);
    }

    public function generateNumero()
    {
        $numero = $this->service->generateNumeroFacture();

        return response()->json([
            'success' => true,
            'numero' => $numero
        ]);
    }

    public function getEnRetard()
    {
        $factures = $this->service->getFacturesEnRetard();

        return response()->json([
            'success' => true,
            'data' => $factures
        ]);
    }
}
