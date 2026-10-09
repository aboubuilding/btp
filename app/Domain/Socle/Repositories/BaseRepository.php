<?php
namespace App\Domain\Socle\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements BaseRepositoryInterface
{
    /** @var Model Instance du modèle Eloquent */
    protected Model $model;

    /** Colonnes éligibles aux filtres par défaut. */
    protected array $filtresSimples = [];

    /** Colonnes éligibles à la recherche "search". */
    protected array $colonnesSearch = [];

    /** Relations à charger par défaut. */
    protected array $with = [];

    /** Colonne de tri par défaut. */
    protected string $orderBy = 'created_at';

    /** Direction de tri par défaut. */
    protected string $orderDir = 'desc';

    /** Colonne pour la pagination / chunking. */
    protected string $primaryKey = 'id';

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->primaryKey = $model->getKeyName();
    }

    // ================================================
    // LECTURE
    // ================================================

    public function find(int $id): ?Model
    {
        return $this->newQuery()->find($id);
    }

    public function findOrFail(int $id): Model
    {
        return $this->newQuery()->findOrFail($id);
    }

    public function all(array $colonnes = ['*']): Collection
    {
        return $this->newQuery()->get($colonnes);
    }

    public function paginate(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        $query = $this->newQuery()
            ->when($this->model->getConnection()->getSchemaBuilder()->hasColumn($this->model->getTable(), 'etat')
                && !($filtres['include_inactif'] ?? false),
                fn($q) => $q->where('etat', 1)
            )
            ->when($filtres, fn($q) => $this->appliquerFiltres($q, $filtres))
            ->orderBy($filtres['order_by'] ?? $this->orderBy, $filtres['order_dir'] ?? $this->orderDir);

        return $query->paginate($parPage)->withQueryString();
    }

    public function count(array $filtres = []): int
    {
        return $this->newQuery()
            ->when($filtres, fn($q) => $this->appliquerFiltres($q, $filtres))
            ->count();
    }

    public function exists(array $criteria): bool
    {
        return $this->newQuery()->where($criteria)->exists();
    }

    // ================================================
    // ÉCRITURE
    // ================================================

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(Model $model, array $data): Model
    {
        $model->update($data);
        return $model->fresh();
    }

    public function delete(Model $model): bool
    {
        return $model->delete();
    }

    public function desactiver(Model $model): bool
    {
        return $model->update(['etat' => 0]);
    }

    public function reactiver(Model $model): bool
    {
        return $model->update(['etat' => 1]);
    }

    // ================================================
    // HELPERS PROTÉGÉS
    // ================================================

    protected function newQuery()
    {
        return $this->model->newQuery()
            ->when($this->with, fn($q) => $q->with($this->with));
    }

    protected function appliquerFiltres($query, array $filtres)
    {
        // Recherche globale
        if (!empty($filtres['search']) && !empty($this->colonnesSearch)) {
            $term = $filtres['search'];
            $query->where(function ($q) use ($term) {
                foreach ($this->colonnesSearch as $col) {
                    $q->orWhere($col, 'like', "%{$term}%");
                }
            });
        }

        // Filtres simples (égalité)
        foreach ($this->filtresSimples as $col) {
            if (!empty($filtres[$col])) {
                $query->where($col, $filtres[$col]);
            }
        }

        // Filtres spéciaux : période
        if (!empty($filtres['date_debut']) && !empty($filtres['date_fin'])) {
            $colDate = $filtres['date_colonne'] ?? 'created_at';
            $query->whereBetween($colDate, [$filtres['date_debut'], $filtres['date_fin']]);
        }

        return $query;
    }

    /** Retourne la colonne "code" si elle existe. */
    protected function colonneCode(): ?string
    {
        $schema = $this->model->getConnection()->getSchemaBuilder();
        return $schema->hasColumn($this->model->getTable(), 'code') ? 'code' : null;
    }

    protected function findByCode(string $code): ?Model
    {
        $col = $this->colonneCode();
        if (!$col) return null;
        return $this->newQuery()->where($col, $code)->first();
    }
}