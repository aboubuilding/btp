<?php
namespace App\Domain\ParcMateriel\Repositories;

use App\Domain\ParcMateriel\Models\PanneEquipement;
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentPanneRepository extends BaseRepository implements PanneRepositoryInterface
{
    protected array $with = ['equipement', 'declarePar'];
    protected array $filtresSimples = ['statut', 'equipement_id'];
    protected string $orderBy = 'date_panne';

    public function __construct(PanneEquipement $model)
    {
        parent::__construct($model);
    }

    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->paginate($filtres, $parPage);
    }

    public function parEquipement(int $equipementId): Collection
    {
        return $this->newQuery()->where('equipement_id', $equipementId)->latest('date_panne')->get();
    }

    public function enCours(): Collection
    {
        return $this->newQuery()->whereIn('statut', ['declaree', 'en_reparation'])->get();
    }

    public function nonCloturees(): Collection
    {
        return $this->newQuery()->whereNull('date_cloture')->get();
    }

    public function coutTotal(): float
    {
        return (float) $this->model->sum('cout_reparation');
    }

    public function dureeMoyenneImmobilisation(): float
    {
        return round((float) $this->model->avg('heures_immobilisation'), 2);
    }

    public function statistiques(): array
    {
        return [
            'total'         => $this->model->count(),
            'en_cours'      => $this->model->whereIn('statut', ['declaree', 'en_reparation'])->count(),
            'cloturees'     => $this->model->where('statut', 'cloturee')->count(),
            'cout_total'    => $this->coutTotal(),
            'duree_moy_h'   => $this->dureeMoyenneImmobilisation(),
            'ce_mois'       => $this->model->whereMonth('date_panne', now()->month)->count(),
        ];
    }
}