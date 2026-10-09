<?php
namespace App\Domain\QHSE\Repositories;

use App\Domain\QHSE\Models\IncidentSecurite;
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentIncidentRepository extends BaseRepository implements IncidentRepositoryInterface
{
    protected array $with = ['projet', 'declarePar'];
    protected array $colonnesSearch = ['description', 'causes'];
    protected array $filtresSimples = ['gravite', 'type', 'statut', 'projet_id'];
    protected string $orderBy = 'date_incident';

    public function __construct(IncidentSecurite $model)
    {
        parent::__construct($model);
    }

    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->paginate($filtres, $parPage);
    }

    public function avecDetails(IncidentSecurite $incident): IncidentSecurite
    {
        return $incident->load(['projet', 'declarePar', 'documents']);
    }

    public function parProjet(int $projetId): Collection
    {
        return $this->newQuery()->where('projet_id', $projetId)->latest('date_incident')->get();
    }

    public function graves(): Collection
    {
        return $this->newQuery()->whereIn('gravite', ['grave', 'mortelle'])->get();
    }

    public function ouverts(): Collection
    {
        return $this->newQuery()->whereIn('statut', ['declare', 'en_analyse'])->get();
    }

    public function parPeriode(string $debut, string $fin): Collection
    {
        return $this->newQuery()->whereBetween('date_incident', [$debut, $fin])->get();
    }

    public function statistiques(): array
    {
        return [
            'total'         => $this->model->where('etat', 1)->count(),
            'graves'        => $this->model->whereIn('gravite', ['grave', 'mortelle'])->count(),
            'mineurs'       => $this->model->whereIn('gravite', ['mineure', 'moyenne'])->count(),
            'mois'          => $this->model->whereMonth('date_incident', now()->month)->count(),
            'victimes'      => (int) $this->model->sum('nombre_victimes'),
            'jours_arret'   => (int) $this->model->sum('jours_arret'),
            'ouverts'       => $this->model->whereIn('statut', ['declare', 'en_analyse'])->count(),
            'clos'          => $this->model->where('statut', 'clos')->count(),
        ];
    }

    public function tauxFrequence(int $projetId, string $debut, string $fin): float
    {
        $incidents = $this->model
            ->where('projet_id', $projetId)
            ->whereBetween('date_incident', [$debut, $fin])
            ->where('type', 'accident_travail')
            ->count();

        // Heures travaillées (approx. 8h/jour × présences)
        $heures = (float) \App\Domain\Personnel\Models\Presence::where('projet_id', $projetId)
            ->whereBetween('date', [$debut, $fin])
            ->whereNotNull('valide_le')
            ->sum('heures_travaillees');

        if ($heures === 0) return 0;

        // Taux = (incidents × 1 000 000) / heures travaillées
        return round(($incidents * 1_000_000) / $heures, 2);
    }

    public function joursSansAccident(int $projetId): int
    {
        $dernierIncident = $this->model
            ->where('projet_id', $projetId)
            ->where('type', 'accident_travail')
            ->orderByDesc('date_incident')
            ->first();

        return $dernierIncident
            ? (int) $dernierIncident->date_incident->diffInDays(now())
            : 999;
    }
}