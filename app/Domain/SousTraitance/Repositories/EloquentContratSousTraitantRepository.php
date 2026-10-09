<?php
namespace App\Domain\SousTraitance\Repositories;

use App\Domain\SousTraitance\Models\ContratSousTraitant;
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentContratSousTraitantRepository extends BaseRepository implements ContratSousTraitantRepositoryInterface
{
    protected array $with = ['soustraitant', 'projet'];
    protected array $colonnesSearch = ['numero_contrat'];
    protected array $filtresSimples = ['statut', 'sous_traitant_id', 'projet_id'];
    protected string $orderBy = 'date_debut';

    public function __construct(ContratSousTraitant $model)
    {
        parent::__construct($model);
    }

    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->paginate($filtres, $parPage);
    }

    public function avecDetails(ContratSousTraitant $contrat): ContratSousTraitant
    {
        return $contrat->load([
            'soustraitant', 'projet',
            'factures.paiements',
        ]);
    }

    public function enCours(): Collection
    {
        return $this->newQuery()->where('statut', 'en_cours')->get();
    }

    public function parProjet(int $projetId): Collection
    {
        return $this->newQuery()->where('projet_id', $projetId)->latest('date_debut')->get();
    }

    public function parSousTraitant(int $soustraitantId): Collection
    {
        return $this->newQuery()->where('sous_traitant_id', $soustraitantId)->latest('date_debut')->get();
    }

    public function statistiques(): array
    {
        return [
            'total'      => $this->model->where('etat', 1)->count(),
            'en_cours'   => $this->model->where('statut', 'en_cours')->count(),
            'termines'   => $this->model->where('statut', 'termine')->count(),
            'resilies'   => $this->model->where('statut', 'resilie')->count(),
            'montant_total' => (float) $this->model->where('etat', 1)->sum('montant'),
        ];
    }

    public function genererNumero(): string
    {
        $prefix = 'CT-ST';
        $last = $this->model->whereYear('created_at', now()->year)->max('id') ?? 0;
        return sprintf('%s-%s-%03d', $prefix, now()->year, $last + 1);
    }

    public function montantTotalEngage(): float
    {
        return (float) $this->model->where('etat', 1)->where('statut', 'en_cours')->sum('montant');
    }
}