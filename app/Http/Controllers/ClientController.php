<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Services\ClientService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected ClientService $service;

    public function __construct(ClientService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $clients = $this->service->getAll();
        $stats = $this->service->getStats();
        $types = $this->service->getTypes();

        return view('admin.clients.index', compact('clients', 'stats', 'types'));
    }

    public function store(ClientRequest $request)
    {
        $client = $this->service->create($request->validated());

        if ($client) {
            return response()->json([
                'success' => true,
                'message' => 'Client ajouté avec succès.',
                'data' => $client
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de l\'ajout du client.'
        ], 500);
    }

    public function update(ClientRequest $request, int $id)
    {
        $updated = $this->service->update($id, $request->validated());

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Client mis à jour avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la mise à jour du client.'
        ], 500);
    }

    public function toggleActive(int $id)
    {
        $toggled = $this->service->toggleActive($id);

        if ($toggled) {
            $client = $this->service->getClient($id);
            return response()->json([
                'success' => true,
                'message' => $client && $client->etat === 1
                    ? 'Client activé avec succès.'
                    : 'Client désactivé avec succès.',
                'etat' => $client ? $client->etat : null
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de l\'opération.'
        ], 500);
    }

    public function destroy(int $id)
    {
        $deleted = $this->service->delete($id);

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Client supprimé avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la suppression du client.'
        ], 500);
    }

    public function restore(int $id)
    {
        $restored = $this->service->restore($id);

        if ($restored) {
            return response()->json([
                'success' => true,
                'message' => 'Client restauré avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la restauration du client.'
        ], 500);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('q', '');
        $clients = $this->service->search($keyword);

        return response()->json([
            'success' => true,
            'data' => $clients
        ]);
    }

    public function getClientProjects(int $id)
    {
        $projects = $this->service->getProjectsByClient($id);
        $client = $this->service->getClient($id);

        return response()->json([
            'success' => true,
            'data' => [
                'client' => $client,
                'projects' => $projects
            ]
        ]);
    }
}
