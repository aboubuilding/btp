<?php
namespace App\Domain\Commercial\Repositories;

use App\Domain\Commercial\Models\Devis;
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentDevisRepository extends BaseRepository implements DevisRepositoryInterface
{
    protected array $with = ['client'];
    protected array $colonnesSearch = ['numero', 'objet'];
    protected array $filtresSimples = ['statut', 'client_id'];
    protected string $orderBy = 'date_devis';

    public function __construct(Devis $model)
    {
        parent::__construct($model);
    }

    public function paginateAvecClient(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->paginate($filtres, $parPage);
    }

    public function avecLignes(Devis $devis): Devis
    {
        return $devis->load(['client', 'lots', 'lignes.lot', 'marche']);
    }

    public function acceptes(): Collection
    {
        return $this->newQuery()->where('statut', 'accepte')->get();
    }

    public function enAttente(): Collection
    {
        return $this->newQuery()->where('statut', 'envoye')->get();
    }

    public function parClient(int $clientId): Collection
    {
        return $this->newQuery()->where('client_id', $clientId)->latest('date_devis')->get();
    }

    public function statistiques(): array
    {
        $total = $this->model->where('etat', 1)->count();
        $montantTotal = $this->model->where('etat', 1)->sum('montant_ttc');
        $montantAccepte = $this->model->where('statut', 'accepte')->sum('montant_ttc');

        return [
            'total'             => $total,
            'brouillons'        => $this->model->where('statut', 'brouillon')->count(),
            'envoyes'           => $this->model->where('statut', 'envoye')->count(),
            'acceptes'          => $this->model->where('statut', 'accepte')->count(),
            'refuses'           => $this->model->where('statut', 'refuse')->count(),
            'montant_total'     => (float) $montantTotal,
            'montant_accepte'   => (float) $montantAccepte,
            'taux_conversion'   => $total > 0
                ? round(($this->model->where('statut', 'accepte')->count() / $total) * 100, 2)
                : 0,
        ];
    }

    public function genererNumero(): string
    {
        $prefix = 'DEV';
        $last = $this->model->whereYear('created_at', now()->year)->max('id') ?? 0;
        return sprintf('%s-%s-%04d', $prefix, now()->year, $last + 1);
    }

    public function margeMoyenne(): float
    {
        $devis = $this->model->where('etat', 1)
            ->where('montant_ht', '>', 0)
            ->get();

        if ($devis->isEmpty()) return 0;

        $marges = $devis->map(fn($d) => $d->taux_marge);
        return round($marges->avg(), 2);
    }
}