<?php
namespace App\Domain\ParcMateriel\Repositories;

use App\Domain\ParcMateriel\Models\MaintenanceEquipement;
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentMaintenanceRepository extends BaseRepository implements MaintenanceRepositoryInterface
{
    protected array $with = ['equipement'];
    protected array $filtresSimples = ['type', 'equipement_id'];
    protected string $orderBy = 'date_intervention';

    public function __construct(MaintenanceEquipement $model)
    {
        parent::__construct($model);
    }

    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->paginate($filtres, $parPage);
    }

    public function parEquipement(int $equipementId): Collection
    {
        return $this->newQuery()->where('equipement_id', $equipementId)->latest('date_intervention')->get();
    }

    public function preventives(int $equipementId): Collection
    {
        return $this->newQuery()
            ->where('equipement_id', $equipementId)
            ->where('type', 'preventive')
            ->get();
    }

    public function correctives(int $equipementId): Collection
    {
        return $this->newQuery()
            ->where('equipement_id', $equipementId)
            ->where('type', 'corrective')
            ->get();
    }

    public function echeanceProche(int $jours = 30): Collection
    {
        return $this->newQuery()
            ->whereNotNull('prochaine_echeance')
            ->whereDate('prochaine_echeance', '<=', now()->addDays($jours))
            ->orderBy('prochaine_echeance')
            ->get();
    }

    public function coutTotal(): float
    {
        return (float) $this->model->sum('cout');
    }
}