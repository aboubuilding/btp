<?php
namespace App\Domain\Personnel\Repositories;

use App\Domain\Personnel\Models\BulletinPaie;
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentBulletinPaieRepository extends BaseRepository implements BulletinPaieRepositoryInterface
{
    protected array $with = ['employe', 'periode'];
    protected array $filtresSimples = ['statut', 'periode_paie_id', 'employee_id'];
    protected string $orderBy = 'created_at';

    public function __construct(BulletinPaie $model)
    {
        parent::__construct($model);
    }

    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->paginate($filtres, $parPage);
    }

    public function parPeriode(int $periodeId): Collection
    {
        return $this->newQuery()->where('periode_paie_id', $periodeId)->get();
    }

    public function parEmploye(int $employeId): Collection
    {
        return $this->newQuery()
            ->where('employee_id', $employeId)
            ->with('periode')
            ->latest()
            ->get();
    }

    public function dansPeriode(int $employeId, int $periodeId): ?BulletinPaie
    {
        return $this->newQuery()
            ->where('employee_id', $employeId)
            ->where('periode_paie_id', $periodeId)
            ->first();
    }

    public function statistiques(?int $periodeId = null): array
    {
        $q = $this->model->newQuery();
        if ($periodeId) $q->where('periode_paie_id', $periodeId);

        return [
            'total'              => (clone $q)->count(),
            'brouillons'         => (clone $q)->where('statut', 'brouillon')->count(),
            'valides'            => (clone $q)->where('statut', 'valide')->count(),
            'payes'              => (clone $q)->where('statut', 'paye')->count(),
            'total_brut'         => (float) (clone $q)->sum('brut'),
            'total_net'          => (float) (clone $q)->sum('net_a_payer'),
            'total_cotisations'  => (float) (clone $q)->sum('cotisations_salariales'),
            'total_impots'       => (float) (clone $q)->sum('impot_revenu'),
        ];
    }

    public function masseSalarialePeriode(int $periodeId): array
    {
        $bulletins = $this->model->where('periode_paie_id', $periodeId)->get();

        return [
            'brut_total'          => (float) $bulletins->sum('brut'),
            'net_total'           => (float) $bulletins->sum('net_a_payer'),
            'cotis_salariales'    => (float) $bulletins->sum('cotisations_salariales'),
            'cotis_patronales'    => (float) $bulletins->sum('cotisations_patronales'),
            'impot_total'         => (float) $bulletins->sum('impot_revenu'),
            'avances_deduites'    => (float) $bulletins->sum('avances_deduites'),
            'cout_employeur'      => (float) $bulletins->sum('brut') + (float) $bulletins->sum('cotisations_patronales'),
        ];
    }

    public function genererNumero(): string
    {
        $prefix = 'BP';
        $last = $this->model->whereYear('created_at', now()->year)->max('id') ?? 0;
        return sprintf('%s-%s-%04d', $prefix, now()->year, $last + 1);
    }
}