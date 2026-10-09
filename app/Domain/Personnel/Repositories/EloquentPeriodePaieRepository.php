<?php
namespace App\Domain\Personnel\Repositories;

use App\Domain\Personnel\Models\PeriodePaie;
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentPeriodePaieRepository extends BaseRepository implements PeriodePaieRepositoryInterface
{
    protected array $filtresSimples = ['statut', 'type'];
    protected string $orderBy = 'date_debut';

    public function __construct(PeriodePaie $model)
    {
        parent::__construct($model);
    }

    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->model->newQuery()
            ->withCount('bulletins')
            ->when(!empty($filtres['statut']), fn($q) => $q->where('statut', $filtres['statut']))
            ->when(!empty($filtres['type']), fn($q) => $q->where('type', $filtres['type']))
            ->orderByDesc('date_debut')
            ->paginate($parPage)
            ->withQueryString();
    }

    public function avecBulletins(PeriodePaie $periode): PeriodePaie
    {
        return $periode->load(['bulletins.employe.poste']);
    }

    public function ouverte(): ?PeriodePaie
    {
        return $this->newQuery()->where('statut', 'ouverte')->latest('date_debut')->first();
    }

    public function parType(string $type): Collection
    {
        return $this->newQuery()->where('type', $type)->latest('date_debut')->get();
    }

    public function statistiques(): array
    {
        return [
            'total'     => $this->model->count(),
            'ouvertes'  => $this->model->where('statut', 'ouverte')->count(),
            'cloturees' => $this->model->where('statut', 'cloturee')->count(),
            'payees'    => $this->model->where('statut', 'payee')->count(),
        ];
    }
}