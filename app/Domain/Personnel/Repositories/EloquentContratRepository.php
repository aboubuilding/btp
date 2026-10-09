<?php
namespace App\Domain\Personnel\Repositories;

use App\Domain\Personnel\Models\Contrat;
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentContratRepository extends BaseRepository implements ContratRepositoryInterface
{
    protected array $with = ['employe'];
    protected array $colonnesSearch = ['numero'];
    protected array $filtresSimples = ['statut', 'type', 'employee_id'];
    protected string $orderBy = 'date_debut';

    public function __construct(Contrat $model)
    {
        parent::__construct($model);
    }

    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->paginate($filtres, $parPage);
    }

    public function parEmploye(int $employeId): Collection
    {
        return $this->newQuery()->where('employee_id', $employeId)->latest('date_debut')->get();
    }

    public function actifs(): Collection
    {
        return $this->newQuery()->where('statut', 'en_cours')->get();
    }

    public function expirentBientot(int $jours = 30): Collection
    {
        return $this->newQuery()
            ->where('statut', 'en_cours')
            ->whereNotNull('date_fin')
            ->whereDate('date_fin', '<=', now()->addDays($jours))
            ->orderBy('date_fin')
            ->get();
    }

    public function statistiques(): array
    {
        return [
            'total'      => $this->model->count(),
            'en_cours'   => $this->model->where('statut', 'en_cours')->count(),
            'termines'   => $this->model->where('statut', 'termine')->count(),
            'resilies'   => $this->model->where('statut', 'resilie')->count(),
            'cdi'        => $this->model->where('type', 'cdi')->count(),
            'cdd'        => $this->model->where('type', 'cdd')->count(),
        ];
    }

    public function genererNumero(): string
    {
        $prefix = 'CT';
        $last = $this->model->whereYear('created_at', now()->year)->max('id') ?? 0;
        return sprintf('%s-%s-%04d', $prefix, now()->year, $last + 1);
    }
}