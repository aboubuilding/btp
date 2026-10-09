<?php
namespace App\Domain\Personnel\Repositories;

use App\Domain\Personnel\Models\Presence;
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentPresenceRepository extends BaseRepository implements PresenceRepositoryInterface
{
    protected array $with = ['employe', 'projet'];
    protected array $filtresSimples = ['projet_id', 'employee_id', 'statut'];

    public function __construct(Presence $model)
    {
        parent::__construct($model);
    }

    public function paginateAvecRelations(array $filtres = [], int $parPage = 50): LengthAwarePaginator
    {
        return $this->paginate($filtres, $parPage);
    }

    public function parJour(int $projetId, string $date): Collection
    {
        return $this->newQuery()
            ->where('projet_id', $projetId)
            ->whereDate('date', $date)
            ->get();
    }

    public function parEmploye(int $employeId, ?string $debut = null, ?string $fin = null): Collection
    {
        return $this->newQuery()
            ->where('employee_id', $employeId)
            ->when($debut && $fin, fn($q) => $q->whereBetween('date', [$debut, $fin]))
            ->orderByDesc('date')
            ->get();
    }

    public function valideesPeriode(int $employeId, string $debut, string $fin): Collection
    {
        return $this->newQuery()
            ->where('employee_id', $employeId)
            ->whereBetween('date', [$debut, $fin])
            ->whereNotNull('valide_le')
            ->get();
    }

    public function duJour(int $employeId, string $date): ?Presence
    {
        return $this->newQuery()
            ->where('employee_id', $employeId)
            ->whereDate('date', $date)
            ->first();
    }

    public function heuresTravaillees(int $employeId, string $debut, string $fin): float
    {
        return (float) $this->model
            ->where('employee_id', $employeId)
            ->whereBetween('date', [$debut, $fin])
            ->whereNotNull('valide_le')
            ->sum('heures_travaillees');
    }

    public function heuresParProjet(int $projetId, string $debut, string $fin): Collection
    {
        return $this->model
            ->where('projet_id', $projetId)
            ->whereBetween('date', [$debut, $fin])
            ->whereNotNull('valide_le')
            ->select('employee_id', DB::raw('SUM(heures_travaillees) as total_heures'))
            ->groupBy('employee_id')
            ->with('employe')
            ->get();
    }

    public function aValider(int $projetId, ?string $date = null): Collection
    {
        return $this->newQuery()
            ->where('projet_id', $projetId)
            ->when($date, fn($q) => $q->whereDate('date', $date))
            ->whereNull('valide_le')
            ->get();
    }

    public function statistiques(int $projetId, string $debut, string $fin): array
    {
        $base = $this->model->where('projet_id', $projetId)->whereBetween('date', [$debut, $fin]);

        return [
            'total_jours'    => (clone $base)->count(),
            'presents'       => (clone $base)->where('statut', 'present')->count(),
            'absents'        => (clone $base)->where('statut', 'absent')->count(),
            'retards'        => (clone $base)->where('statut', 'retard')->count(),
            'heures_totales' => (float) (clone $base)->sum('heures_travaillees'),
            'heures_sup'     => (float) (clone $base)->sum('heures_supplementaires'),
            'validees'       => (clone $base)->whereNotNull('valide_le')->count(),
            'en_attente'     => (clone $base)->whereNull('valide_le')->count(),
        ];
    }
}