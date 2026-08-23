<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContratSousTraitantRequest;
use App\Services\ContratSousTraitantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContratSousTraitantController extends Controller
{
    protected ContratSousTraitantService $service;

    public function __construct(ContratSousTraitantService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $contrats = $this->service->getAll();
        $stats = $this->service->getStats();
        $soustraitants = $this->service->getSoustraitants();
        $projets = $this->service->getProjets();
        $statuts = $this->service->getStatuts();

        return view('admin.contrats-sous-traitants.index', compact(
            'contrats',
            'stats',
            'soustraitants',
            'projets',
            'statuts'
        ));
    }

    public function store(ContratSousTraitantRequest $request)
    {
        $data = $request->validated();

        // Générer le numéro de contrat si non fourni
        if (empty($data['numero_contrat'])) {
            $data['numero_contrat'] = $this->service->generateNumeroContrat();
        }

        // Gérer le fichier
        if ($request->hasFile('fichier')) {
            $path = $this->service->uploadFile($request->file('fichier'), $data['numero_contrat']);
            if ($path) {
                $data['chemin_fichier'] = $path;
            }
        }

        $contrat = $this->service->create($data);

        if ($contrat) {
            return response()->json([
                'success' => true,
                'message' => 'Contrat ajouté avec succès.',
                'data' => $contrat
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de l\'ajout du contrat.'
        ], 500);
    }

    public function update(ContratSousTraitantRequest $request, int $id)
    {
        $data = $request->validated();

        // Gérer le fichier
        if ($request->hasFile('fichier')) {
            $contrat = $this->service->getContrat($id);
            if ($contrat && $contrat->chemin_fichier) {
                $this->service->deleteFile($contrat->chemin_fichier);
            }

            $path = $this->service->uploadFile($request->file('fichier'), $data['numero_contrat']);
            if ($path) {
                $data['chemin_fichier'] = $path;
            }
        }

        $updated = $this->service->update($id, $data);

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Contrat mis à jour avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la mise à jour du contrat.'
        ], 500);
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'statut' => ['required', 'string', 'in:en_cours,termine,resilie'],
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

    public function downloadFile(int $id)
    {
        $contrat = $this->service->getContrat($id);

        if (!$contrat || !$contrat->chemin_fichier) {
            return response()->json([
                'success' => false,
                'message' => 'Fichier non trouvé.'
            ], 404);
        }

        $download = $this->service->downloadFile($contrat->chemin_fichier);

        if ($download) {
            return $download;
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors du téléchargement du fichier.'
        ], 500);
    }

    public function destroy(int $id)
    {
        $deleted = $this->service->delete($id);

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Contrat supprimé avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la suppression du contrat.'
        ], 500);
    }

    public function restore(int $id)
    {
        $restored = $this->service->restore($id);

        if ($restored) {
            return response()->json([
                'success' => true,
                'message' => 'Contrat restauré avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la restauration du contrat.'
        ], 500);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('q', '');
        $contrats = $this->service->search($keyword);

        return response()->json([
            'success' => true,
            'data' => $contrats
        ]);
    }

    public function generateNumero()
    {
        $numero = $this->service->generateNumeroContrat();

        return response()->json([
            'success' => true,
            'numero' => $numero
        ]);
    }
}
