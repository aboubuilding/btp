<?php
namespace App\Domain\Approvisionnement\Repositories;

use App\Domain\Approvisionnement\Models\Livraison;
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentLivraisonRepository extends BaseRepository implements LivraisonRepositoryInterface
{
    protected array $with = ['bonCommande', 'entrepot', 'receptionnaire'];
    protected array $colonnesSearch = ['numero'];
    protected array $filtresSimples = ['statut', 'entrepot_id', 'bon_commande_id'];
    protected string $orderBy = 'date_livraison';

    public function __construct(Livraison $model)
    {
        parent::__construct($model);
    }

    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->paginate($filtres, $parPage);
    }

    public function avecDetails(Livraison $livraison): Livraison
    {
        return $livraison->load(['bonCommande', 'entrepot', 'receptionnaire', 'articles.materiau']);
    }

    public function parBonCommande(int $bcId): Collection
    {
        return $this->newQuery()->where('bon_commande_id', $bcId)->latest()->get();
    }

    public function parEntrepot(int $entrepotId): Collection
    {
        return $this->newQuery()->where('entrepot_id', $entrepotId)->latest()->get();
    }

    public function statistiques(): array
    {
        return [
            'total'     => $this->model->count(),
            'partielles'=> $this->model->where('statut', 'partielle')->count(),
            'completes' => $this->model->where('statut', 'complete')->count(),
            'refusees'  => $this->model->where('statut', 'refusee')->count(),
            'ce_mois'   => $this->model->whereMonth('date_livraison', now()->month)->count(),
        ];
    }

    public function genererNumero(): string
    {
        $prefix = 'LIV';
        $last = $this->model->whereYear('created_at', now()->year)->max('id') ?? 0;
        return sprintf('%s-%s-%04d', $prefix, now()->year, $last + 1);
    }
}