<?php

namespace App\Repositories\Eloquent;

use App\Models\PlanComptable;
use App\Repositories\Interfaces\PlanComptableRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PlanComptableRepository extends BaseRepository implements PlanComptableRepositoryInterface
{
    public function model(): string
    {
        return PlanComptable::class;
    }

    public function getRootAccounts(): array
    {
        return $this->activeQuery()
            ->whereNull('parent_id')
            ->orderBy('code')
            ->get()
            ->toArray();
    }

    public function getTree(): array
    {
        $all = $this->activeQuery()
            ->orderBy('code')
            ->get()
            ->toArray();

        return $this->buildTree($all);
    }

    private function buildTree(array $elements, $parentId = null): array
    {
        $branch = [];

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }

    public function getByType(string $type): array
    {
        return $this->activeQuery()
            ->where('type', $type)
            ->orderBy('code')
            ->get()
            ->toArray();
    }

    public function search(string $keyword): array
    {
        return $this->activeQuery()
            ->where(function ($query) use ($keyword) {
                $query->where('code', 'LIKE', "%{$keyword}%")
                    ->orWhere('nom', 'LIKE', "%{$keyword}%");
            })
            ->orderBy('code')
            ->get()
            ->toArray();
    }

    public function getStats(): array
    {
        $total = $this->activeQuery()->count();

        $byType = $this->activeQuery()
            ->select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->get()
            ->toArray();

        $rootCount = $this->activeQuery()->whereNull('parent_id')->count();

        $avecEnfants = $this->activeQuery()
            ->whereHas('enfants', function ($query) {
                $query->where('etat', 1);
            })
            ->count();

        return [
            'total' => $total,
            'by_type' => $byType,
            'root_count' => $rootCount,
            'avec_enfants' => $avecEnfants,
        ];
    }

    public function getAvailableParents(int $excludeId = null): array
    {
        $query = $this->activeQuery()
            ->whereNull('parent_id')
            ->orderBy('code');

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->get()->toArray();
    }

    public function reorder(int $id, ?int $parentId): bool
    {
        $compte = $this->find($id);
        if (!$compte) {
            return false;
        }

        // Vérifier qu'on ne crée pas une boucle
        if ($parentId) {
            $parent = $this->find($parentId);
            if ($parent && $this->isAncestor($id, $parentId)) {
                return false;
            }
        }

        $compte->parent_id = $parentId;
        return $compte->save();
    }

    private function isAncestor(int $compteId, int $parentId): bool
    {
        $current = $this->find($parentId);
        while ($current) {
            if ($current->id == $compteId) {
                return true;
            }
            $current = $current->parent;
        }
        return false;
    }
}
