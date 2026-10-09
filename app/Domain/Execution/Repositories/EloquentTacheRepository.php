<?php
namespace App\Domain\Execution\Repositories;

use App\Domain\Execution\Models\Tache;
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentTacheRepository extends BaseRepository implements TacheRepositoryInterface
{
    protected array $with = ['phase', 'assigne', 'ligneDevis'];
    protected array $colonnesSearch = ['nom', 'description'];
    protected array $filtresSimples = ['statut', 'priorite', 'phase_id', 'assigne_a', 'projet_id'];

    public function __construct(Tache $model)
    {
        parent::__construct($model);
    }

    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->paginate($filtres, $parPage);
    }

    public function parProjet(int $projetId): Collection
    {
        return $this->newQuery()->where('projet_id', $projetId)->orderBy('date_debut')->get();
    }

    public function parPhase(int $phaseId): Collection
    {
        return $this->newQuery()->where('phase_id', $phaseId)->orderBy('date_debut')->get();
    }

    public function enRetard(): Collection
    {
        return $this->newQuery()->enRetard()->get();
    }

    public function enRetardParProjet(int $projetId): Collection
    {
        return $this->newQuery()->where('projet_id', $projetId)->enRetard()->get();
    }

    public function assigneesA(int $employeId): Collection
    {
        return $this->newQuery()->where('assigne_a', $employeId)->get();
    }

    public function planifiables(int $projetId): Collection
    {
        return $this->newQuery()
            ->where('projet_id', $projetId)
            ->whereIn('statut', ['a_faire'])
            ->whereDoesntHave('dependances', function ($q) {
                $q->whereHas('dependDe', fn($qq) => $qq->where('statut', '!=', 'termine'));
            })
            ->get();
    }

    public function statistiques(int $projetId): array
    {
        $base = $this->model->where('projet_id', $projetId);

        return [
            'total'        => (clone $base)->count(),
            'a_faire'      => (clone $base)->where('statut', 'a_faire')->count(),
            'en_cours'     => (clone $base)->where('statut', 'en_cours')->count(),
            'termines'     => (clone $base)->where('statut', 'termine')->count(),
            'en_retard'    => (clone $base)->enRetard()->count(),
            'avancement'   => (clone $base)->avg('pourcentage_avancement') ?? 0,
            'critiques'    => (clone $base)->where('priorite', 'critique')->where('statut', '!=', 'termine')->count(),
        ];
    }

    public function prochainesEcheances(int $jours = 7): Collection
    {
        return $this->newQuery()
            ->whereDate('date_fin', '>=', now())
            ->whereDate('date_fin', '<=', now()->addDays($jours))
            ->where('statut', '!=', 'termine')
            ->orderBy('date_fin')
            ->get();
    }
}