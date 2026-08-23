<?php

namespace App\Services;

use App\Models\PlanComptable;
use App\Repositories\Interfaces\PlanComptableRepositoryInterface;
use Illuminate\Support\Facades\Log;

class PlanComptableService
{
    protected PlanComptableRepositoryInterface $repository;

    public function __construct(PlanComptableRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): array
    {
        try {
            return $this->repository->getTree();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du plan comptable: ' . $e->getMessage());
            return [];
        }
    }

    public function getFlatList(): array
    {
        try {
            return $this->repository->withSupprime()->orderBy('code')->get()->toArray();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du plan comptable: ' . $e->getMessage());
            return [];
        }
    }

    public function getCompte(int $id): ?PlanComptable
    {
        try {
            return $this->repository->withSupprime()->with(['parent', 'enfants'])->find($id);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du compte: ' . $e->getMessage());
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
                'by_type' => [],
                'root_count' => 0,
                'avec_enfants' => 0,
            ];
        }
    }

    public function getTypes(): array
    {
        return PlanComptable::getTypes();
    }

    public function getParents(int $excludeId = null): array
    {
        try {
            return $this->repository->getAvailableParents($excludeId);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des parents: ' . $e->getMessage());
            return [];
        }
    }

    public function create(array $data): ?PlanComptable
    {
        try {
            $data['etat'] = 1;
            return $this->repository->create($data);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du compte: ' . $e->getMessage());
            return null;
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            return $this->repository->update($id, $data);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du compte: ' . $e->getMessage());
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            // Vérifier si le compte a des enfants
            $compte = $this->getCompte($id);
            if ($compte && $compte->enfants->count() > 0) {
                return false;
            }
            return $this->repository->delete($id);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du compte: ' . $e->getMessage());
            return false;
        }
    }

    public function restore(int $id): bool
    {
        try {
            return $this->repository->restore($id);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la restauration du compte: ' . $e->getMessage());
            return false;
        }
    }

    public function reorder(int $id, ?int $parentId): bool
    {
        try {
            return $this->repository->reorder($id, $parentId);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la réorganisation du compte: ' . $e->getMessage());
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

    public function getByType(string $type): array
    {
        try {
            return $this->repository->getByType($type);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération par type: ' . $e->getMessage());
            return [];
        }
    }

    public function getTreeFlat(): array
    {
        try {
            $tree = $this->repository->getTree();
            $flat = [];
            $this->flattenTree($tree, $flat);
            return $flat;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération de l\'arbre: ' . $e->getMessage());
            return [];
        }
    }

    private function flattenTree(array $tree, array &$flat, $prefix = ''): void
    {
        foreach ($tree as $node) {
            $node['indent'] = $prefix;
            $flat[] = $node;
            if (isset($node['children']) && !empty($node['children'])) {
                $this->flattenTree($node['children'], $flat, $prefix . '— ');
            }
        }
    }
}
