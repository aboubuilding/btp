<?php

namespace App\Services;

use App\Models\Client;
use App\Repositories\Interfaces\ClientRepositoryInterface;
use App\Repositories\Interfaces\ProjetRepositoryInterface;
use Illuminate\Support\Facades\Log;

class ClientService
{
    protected ClientRepositoryInterface $clientRepository;
    protected ProjetRepositoryInterface $projetRepository;

    public function __construct(
        ClientRepositoryInterface $clientRepository,
        ProjetRepositoryInterface $projetRepository
    ) {
        $this->clientRepository = $clientRepository;
        $this->projetRepository = $projetRepository;
    }

    public function getAll(): array
    {
        try {
            return $this->clientRepository->getClientsWithProjects();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des clients: ' . $e->getMessage());
            return [];
        }
    }

    public function getClient(int $id): ?Client
    {
        try {
            return $this->clientRepository->find($id);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du client: ' . $e->getMessage());
            return null;
        }
    }

    public function getClientWithProjects(int $id): ?Client
    {
        try {
            return $this->clientRepository->withSupprime()
                ->with(['projets' => function ($query) {
                    $query->where('etat', 1);
                }])
                ->find($id);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du client: ' . $e->getMessage());
            return null;
        }
    }

    public function getStats(): array
    {
        try {
            return $this->clientRepository->getStats();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des statistiques: ' . $e->getMessage());
            return [
                'total' => 0,
                'actifs' => 0,
                'inactifs' => 0,
                'types' => [],
            ];
        }
    }

    public function getTypes(): array
    {
        return Client::getTypes();
    }

    public function create(array $data): ?Client
    {
        try {
            $data['etat'] = 1;
            return $this->clientRepository->create($data);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du client: ' . $e->getMessage());
            return null;
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            return $this->clientRepository->update($id, $data);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du client: ' . $e->getMessage());
            return false;
        }
    }

    public function toggleActive(int $id): bool
    {
        try {
            return $this->clientRepository->toggleActive($id);
        } catch (\Exception $e) {
            Log::error('Erreur lors du basculement du client: ' . $e->getMessage());
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            return $this->clientRepository->delete($id);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du client: ' . $e->getMessage());
            return false;
        }
    }

    public function restore(int $id): bool
    {
        try {
            return $this->clientRepository->restore($id);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la restauration du client: ' . $e->getMessage());
            return false;
        }
    }

    public function search(string $keyword): array
    {
        try {
            return $this->clientRepository->search($keyword);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la recherche: ' . $e->getMessage());
            return [];
        }
    }

    public function getProjectsByClient(int $clientId): array
    {
        try {
            return $this->projetRepository->getProjectsByClient($clientId);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des projets: ' . $e->getMessage());
            return [];
        }
    }
}
