<?php
namespace App\Domain\Finances\Repositories;

use App\Domain\Finances\Models\Depense;
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentDepenseRepository extends BaseRepository implements DepenseRepositoryInterface
{
    protected array $with = ['projet', 'caisse', 'ligneBudget'];
    protected array $colonnesSearch = ['description'];
    protected array $filtresSimples = ['categorie', 'statut', 'projet_id', 'caisse_id'];
    protected string $orderBy = 'date_depense';

    public function __construct(Depense $model)
    {
        parent::__construct($model);
    }

    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->paginate($filtres, $parPage);
    }

    public function avecDetails(Depense $depense): Depense
    {
        return $depense->load(['projet', 'caisse', 'ligneBudget', 'payePar', 'approuvePar']);
    }

    public function enAttente(): Collection
    {
        return $this->newQuery()->where('statut', 'en_attente')->get();
    }

    public function approuvees(): Collection
    {
        return $this->newQuery()->where('statut', 'approuve')->get();
    }

    public function parProjet(int $projetId): Collection
    {
        return $this->newQuery()->where('projet_id', $projetId)->latest('date_depense')->get();
    }

    public function parCategorie(string $categorie): Collection
    {
        return $this->newQuery()->where('categorie', $categorie)->latest('date_depense')->get();
    }

    public function parPeriode(string $debut, string $fin): Collection
    {
        return $this->newQuery()->whereBetween('date_depense', [$debut, $fin])->get();
    }

    public function statistiques(): array
    {
        return [
            'total'       => $this->model->count(),
            'en_attente'  => $this->model->where('statut', 'en_attente')->count(),
            'approuvees'  => $this->model->where('statut', 'approuve')->count(),
            'rejetees'    => $this->model->where('statut', 'rejete')->count(),
            'montant_mois'=> (float) $this->model->whereMonth('date_depense', now()->month)->sum('montant'),
            'montant_total'=> (float) $this->model->where('statut', 'approuve')->sum('montant'),
        ];
    }

    public function totalParProjet(int $projetId): float
    {
        return (float) $this->model
            ->where('projet_id', $projetId)
            ->where('statut', 'approuve')
            ->sum('montant');
    }

    public function totalParCategorie(?int $projetId = null): array
    {
        $q = $this->model->where('statut', 'approuve');
        if ($projetId) $q->where('projet_id', $projetId);

        return $q->selectRaw('categorie, SUM(montant) as total')
            ->groupBy('categorie')
            ->pluck('total', 'categorie')
            ->toArray();
    }
}