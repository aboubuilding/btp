<?php

namespace App\Http\Controllers;

use App\Http\Requests\FactureRequest;
use App\Services\FactureService;
use Illuminate\Http\Request;

class FactureController extends Controller
{
    protected FactureService $service;

    public function __construct(FactureService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $factures = $this->service->getAll();
        $stats = $this->service->getStats();
        $types = $this->service->getTypes();
        $typesFacturable = $this->service->getTypesFacturable();
        $statuts = $this->service->getStatuts();
        $clients = $this->service->getClients();
        $projets = $this->service->getProjets();

        return view('admin.factures.index', compact(
            'factures',
            'stats',
            'types',
            'typesFacturable',
            'statuts',
            'clients',
            'projets'
        ));
    }

    public function store(FactureRequest $request)
    {
        $facture = $this->service->create($request->validated());

        if ($facture) {
            return response()->json([
                'success' => true,
                'message' => 'Facture créée avec succès.',
                'data' => $facture
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la création de la facture.'
        ], 500);
    }

    public function update(FactureRequest $request, int $id)
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

    public function updateStatut(Request $request, int $id)
    {
        $request->validate([
            'statut' => ['required', 'string', 'in:emise,payee,partiellement_payee,annulee,en_retard'],
        ]);

        $updated = $this->service->updateStatut($id, $request->input('statut'));

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
        $updated = $this->service->markAsPaid($id);

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

    public function annuler(int $id)
    {
        $updated = $this->service->cancel($id);

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Facture annulée avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de l\'annulation de la facture.'
        ], 500);
    }

    public function relancer(int $id)
    {
        $relance = $this->service->relancer($id);

        if ($relance) {
            return response()->json([
                'success' => true,
                'message' => 'Relance envoyée avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de l\'envoi de la relance.'
        ], 500);
    }

    public function envoyer(int $id)
    {
        $facture = $this->service->getFacture($id);

        if (!$facture) {
            return response()->json([
                'success' => false,
                'message' => 'Facture non trouvée.'
            ], 404);
        }

        // Ici vous pouvez envoyer l'email de la facture
        // Mail::to($facture->facturable->email)->send(new FactureEmail($facture));

        return response()->json([
            'success' => true,
            'message' => 'Facture envoyée avec succès.'
        ]);
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

    public function getEnRetard()
    {
        $factures = $this->service->getFacturesEnRetard();

        return response()->json([
            'success' => true,
            'data' => $factures
        ]);
    }

    public function getEcheancesProches(Request $request)
    {
        $days = $request->input('days', 7);
        $factures = $this->service->getFacturesEcheanceProche($days);

        return response()->json([
            'success' => true,
            'data' => $factures
        ]);
    }

    public function generateNumero(Request $request)
    {
        $type = $request->input('type', 'client');
        $numero = $this->service->generateNumero($type);

        return response()->json([
            'success' => true,
            'numero' => $numero
        ]);
    }

    public function getTiers(Request $request)
    {
        $type = $request->input('type_facturable', 'Client');
        $tiers = $this->service->getTiersByType($type);

        return response()->json([
            'success' => true,
            'data' => $tiers
        ]);
    }
}
