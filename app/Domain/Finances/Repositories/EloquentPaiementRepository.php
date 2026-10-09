<?php
namespace App\Domain\Finances\Repositories;

use App\Domain\Finances\Models\Paiement;
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentPaiementRepository extends BaseRepository implements PaiementRepositoryInterface
{
    protected array $with = ['compteBancaire', 'caisse'];
    protected array $colonnesSearch = ['numero_paiement', 'reference'];
    protected array $filtresSimples = ['sens', 'mode'];
    protected string $orderBy = 'date_paiement';

    public function __construct(Paiement $model)
    {
        parent::__construct($model);
    }

    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->paginate($filtres, $parPage);
    }

    public function encaissements(): Collection
    {
        return $this->newQuery()->where('sens', 'encaissement')->latest('date_paiement')->get();
    }

    public function decaissements(): Collection
    {
        return $this->newQuery()->where('sens', 'decaissement')->latest('date_paiement')->get();
    }

    public function parPeriode(string $debut, string $fin): Collection
    {
        return $this->newQuery()->whereBetween('date_paiement', [$debut, $fin])->get();
    }

    public function pourFacture(int $factureId): Collection
    {
        return $this->newQuery()
            ->where('type_payable', \App\Domain\Finances\Models\Facture::class)
            ->where('id_payable', $factureId)
            ->latest('date_paiement')
            ->get();
    }

    public function statistiques(): array
    {
        return [
            'total_encaisse'      => (float) $this->model->where('sens', 'encaissement')->sum('montant'),
            'total_decaisse'      => (float) $this->model->where('sens', 'decaissement')->sum('montant'),
            'encaisse_mois'       => (float) $this->model->where('sens', 'encaissement')
                ->whereMonth('date_paiement', now()->month)
                ->sum('montant'),
            'decaisse_mois'       => (float) $this->model->where('sens', 'decaissement')
                ->whereMonth('date_paiement', now()->month)
                ->sum('montant'),
            'nb_encaissements'    => $this->model->where('sens', 'encaissement')->count(),
            'nb_decaissements'    => $this->model->where('sens', 'decaissement')->count(),
        ];
    }

    public function totalEncaissements(string $debut, string $fin): float
    {
        return (float) $this->model
            ->where('sens', 'encaissement')
            ->whereBetween('date_paiement', [$debut, $fin])
            ->sum('montant');
    }

    public function totalDecaissements(string $debut, string $fin): float
    {
        return (float) $this->model
            ->where('sens', 'decaissement')
            ->whereBetween('date_paiement', [$debut, $fin])
            ->sum('montant');
    }

    public function genererNumero(): string
    {
        $prefix = 'PAI';
        $last = $this->model->whereYear('created_at', now()->year)->max('id') ?? 0;
        return sprintf('%s-%s-%04d', $prefix, now()->year, $last + 1);
    }
}