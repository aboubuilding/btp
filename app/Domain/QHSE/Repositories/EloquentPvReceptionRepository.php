<?php
namespace App\Domain\QHSE\Repositories;

use App\Domain\QHSE\Models\PvReception;
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentPvReceptionRepository extends BaseRepository implements PvReceptionRepositoryInterface
{
    protected array $with = ['projet'];
    protected array $filtresSimples = ['type', 'statut', 'projet_id'];
    protected string $orderBy = 'date_reception';

    public function __construct(PvReception $model)
    {
        parent::__construct($model);
    }

    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->model->newQuery()
            ->with('projet')
            ->withCount(['reserves', 'reserves as reserves_ouvertes_count' => fn($q) => $q->where('statut', 'ouverte')])
            ->when(!empty($filtres['type']), fn($q) => $q->where('type', $filtres['type']))
            ->when(!empty($filtres['statut']), fn($q) => $q->where('statut', $filtres['statut']))
            ->when(!empty($filtres['projet_id']), fn($q) => $q->where('projet_id', $filtres['projet_id']))
            ->orderByDesc('date_reception')
            ->paginate($parPage)
            ->withQueryString();
    }

    public function avecDetails(PvReception $pv): PvReception
    {
        return $pv->load(['projet', 'reserves']);
    }

    public function parProjet(int $projetId): Collection
    {
        return $this->newQuery()->where('projet_id', $projetId)->latest('date_reception')->get();
    }

    public function provisoires(int $projetId): Collection
    {
        return $this->newQuery()
            ->where('projet_id', $projetId)
            ->where('type', 'provisoire')
            ->get();
    }

    public function definitifs(int $projetId): Collection
    {
        return $this->newQuery()
            ->where('projet_id', $projetId)
            ->where('type', 'definitive')
            ->get();
    }

    public function avecReservesOuvertes(): Collection
    {
        return $this->newQuery()
            ->whereHas('reserves', fn($q) => $q->where('statut', 'ouverte'))
            ->get();
    }

    public function statistiques(): array
    {
        return [
            'total'         => $this->model->where('etat', 1)->count(),
            'provisoires'   => $this->model->where('type', 'provisoire')->count(),
            'definitifs'    => $this->model->where('type', 'definitive')->count(),
            'avec_reserves' => $this->model->where('avec_reserves', true)->count(),
            'signes'        => $this->model->where('statut', 'signe')->count(),
            'brouillons'    => $this->model->where('statut', 'brouillon')->count(),
        ];
    }

    public function nombreReservesOuvertes(int $pvId): int
    {
        return (int) $this->model->find($pvId)?->reserves()
            ->where('statut', 'ouverte')
            ->count() ?? 0;
    }
}