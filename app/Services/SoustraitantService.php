<?php

namespace App\Services;

use App\Models\Soustraitant;
use App\Repositories\Interfaces\SoustraitantRepositoryInterface;
use Illuminate\Support\Facades\Log;

class SoustraitantService
{
    protected SoustraitantRepositoryInterface $repository;

    public function __construct(SoustraitantRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): array
    {
        try {
            return $this->repository->withSupprime()->orderBy('nom_entreprise')->get()->toArray();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des sous-traitants: ' . $e->getMessage());
            return [];
        }
    }

    public function getActive(): array
    {
        try {
            return $this->repository->getActiveSoustraitants();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des sous-traitants actifs: ' . $e->getMessage());
            return [];
        }
    }

    public function getSoustraitant(int $id): ?Soustraitant
    {
        try {
            return $this->repository->find($id);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du sous-traitant: ' . $e->getMessage());
            return null;
        }
    }

    public function getStats(): array
    {
        try {
            return $this->repository->getStats();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des statistiques: ' . $e->getMessage());
            return [
                'total' => 0,
                'actifs' => 0,
                'suspendus' => 0,
                'blacklistes' => 0,
                'supprimes' => 0,
            ];
        }
    }

    public function create(array $data): ?Soustraitant
    {
        try {
            $data['etat'] = 1;
            $data['statut'] = $data['statut'] ?? 'actif';
            return $this->repository->create($data);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du sous-traitant: ' . $e->getMessage());
            return null;
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            return $this->repository->update($id, $data);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du sous-traitant: ' . $e->getMessage());
            return false;
        }
    }

    public function updateStatus(int $id, string $status): bool
    {
        try {
            return $this->repository->updateStatus($id, $status);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du statut: ' . $e->getMessage());
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            return $this->repository->delete($id);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du sous-traitant: ' . $e->getMessage());
            return false;
        }
    }

    public function restore(int $id): bool
    {
        try {
            return $this->repository->restore($id);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la restauration du sous-traitant: ' . $e->getMessage());
            return false;
        }
    }

    public function forceDelete(int $id): bool
    {
        try {
            return $this->repository->forceDelete($id);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression définitive: ' . $e->getMessage());
            return false;
        }
    }

    public function search(string $keyword): array
    {
        try {
            return $this->repository->search($keyword);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la recherche: ' . $e->getMessage());
            return [];
        }
    }

    public function getSpecialites(): array
    {
        return Soustraitant::getSpecialites();
    }

    public function getStatuts(): array
    {
        return Soustraitant::getStatuts();
    }
}
