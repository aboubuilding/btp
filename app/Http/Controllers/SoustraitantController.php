<?php

namespace App\Http\Controllers;

use App\Http\Requests\SoustraitantRequest;
use App\Services\SoustraitantService;
use Illuminate\Http\Request;

class SoustraitantController extends Controller
{
    protected SoustraitantService $service;

    public function __construct(SoustraitantService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $soustraitants = $this->service->getAll();
        $stats = $this->service->getStats();
        $specialites = $this->service->getSpecialites();
        $statuts = $this->service->getStatuts();

        return view('admin.soustraitants.index', compact(
            'soustraitants',
            'stats',
            'specialites',
            'statuts'
        ));
    }

    public function store(SoustraitantRequest $request)
    {
        $soustraitant = $this->service->create($request->validated());

        if ($soustraitant) {
            return response()->json([
                'success' => true,
                'message' => 'Sous-traitant ajouté avec succès.',
                'data' => $soustraitant
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de l\'ajout du sous-traitant.'
        ], 500);
    }

    public function update(SoustraitantRequest $request, int $id)
    {
        $updated = $this->service->update($id, $request->validated());

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Sous-traitant mis à jour avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la mise à jour du sous-traitant.'
        ], 500);
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'statut' => ['required', 'string', 'in:actif,suspendu,blackliste'],
        ]);

        $updated = $this->service->updateStatus($id, $request->input('statut'));

        if ($updated) {
            $soustraitant = $this->service->getSoustraitant($id);
            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour avec succès.',
                'statut' => $soustraitant ? $soustraitant->statut : null
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la mise à jour du statut.'
        ], 500);
    }

    public function destroy(int $id)
    {
        $deleted = $this->service->delete($id);

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Sous-traitant supprimé avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la suppression du sous-traitant.'
        ], 500);
    }

    public function restore(int $id)
    {
        $restored = $this->service->restore($id);

        if ($restored) {
            return response()->json([
                'success' => true,
                'message' => 'Sous-traitant restauré avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la restauration du sous-traitant.'
        ], 500);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('q', '');
        $soustraitants = $this->service->search($keyword);

        return response()->json([
            'success' => true,
            'data' => $soustraitants
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
