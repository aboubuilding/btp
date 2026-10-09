<?php
namespace App\Domain\Approvisionnement\Repositories;

use App\Domain\Approvisionnement\Models\BonCommande;
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentBonCommandeRepository extends BaseRepository implements BonCommandeRepositoryInterface
{
    protected array $with = ['fournisseur'];
    protected array $colonnesSearch = ['numero'];
    protected array $filtresSimples = ['statut', 'fournisseur_id', 'demande_achat_id'];
    protected string $orderBy = 'date_commande';

    public function __construct(BonCommande $model)
    {
        parent::__construct($model);
    }

    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->paginate($filtres, $parPage);
    }

    public function avecDetails(BonCommande $bc): BonCommande
    {
        return $bc->load(['fournisseur', 'articles.materiau', 'livraisons', 'validePar']);
    }

    public function enCours(): Collection
    {
        return $this->newQuery()->whereIn('statut', ['envoye', 'confirme', 'livre_partiellement'])->get();
    }

    public function parFournisseur(int $fournisseurId): Collection
    {
        return $this->newQuery()->where('fournisseur_id', $fournisseurId)->latest()->get();
    }

    public function statistiques(): array
    {
        return [
            'total'         => $this->model->where('etat', 1)->count(),
            'brouillons'    => $this->model->where('statut', 'brouillon')->count(),
            'en_cours'      => $this->model->whereIn('statut', ['envoye', 'confirme', 'livre_partiellement'])->count(),
            'livres'        => $this->model->where('statut', 'livre')->count(),
            'annules'       => $this->model->where('statut', 'annule')->count(),
            'montant_mois'  => (float) $this->model->whereMonth('date_commande', now()->month)->sum('montant_total'),
        ];
    }

    public function genererNumero(): string
    {
        $prefix = 'BC';
        $last = $this->model->whereYear('created_at', now()->year)->max('id') ?? 0;
        return sprintf('%s-%s-%04d', $prefix, now()->year, $last + 1);
    }

    public function recalculerTotal(BonCommande $bc): void
    {
        $bc->update(['montant_total' => $bc->articles()->sum('montant')]);
    }
}