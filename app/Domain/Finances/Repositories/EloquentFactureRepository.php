<?php
namespace App\Domain\Finances\Repositories;

use App\Domain\Finances\Models\Facture;
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentFactureRepository extends BaseRepository implements FactureRepositoryInterface
{
    protected array $with = ['projet', 'facturable'];
    protected array $colonnesSearch = ['numero_facture'];
    protected array $filtresSimples = ['type', 'statut', 'projet_id'];
    protected string $orderBy = 'date_facture';

    public function __construct(Facture $model)
    {
        parent::__construct($model);
    }

    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->paginate($filtres, $parPage);
    }

    public function avecDetails(Facture $facture): Facture
    {
        return $facture->load([
            'projet', 'facturable', 'situation.attachement.lignes.ligneDevis',
            'paiements', 'bonCommande',
        ]);
    }

    public function clients(): Collection
    {
        return $this->newQuery()->where('type', 'client')->latest('date_facture')->get();
    }

    public function fournisseurs(): Collection
    {
        return $this->newQuery()->where('type', 'fournisseur')->latest('date_facture')->get();
    }

    public function impayees(): Collection
    {
        return $this->newQuery()
            ->whereIn('statut', ['emise', 'partiellement_payee'])
            ->whereRaw('montant_paye < net_a_payer')
            ->orderBy('date_echeance')
            ->get();
    }

    public function enRetard(): Collection
    {
        return $this->newQuery()->enRetard()->get();
    }

    public function parProjet(int $projetId): Collection
    {
        return $this->newQuery()->where('projet_id', $projetId)->latest('date_facture')->get();
    }

    public function parTiers(string $type, int $id): Collection
    {
        return $this->newQuery()
            ->where('type_facturable', $type)
            ->where('id_facturable', $id)
            ->latest('date_facture')
            ->get();
    }

    public function statistiques(): array
    {
        $facturesClients = $this->model->where('type', 'client')->where('statut', '!=', 'annulee');
        $facturesFournisseurs = $this->model->where('type', 'fournisseur')->where('statut', '!=', 'annulee');

        return [
            'factures_clients'       => (float) (clone $facturesClients)->sum('montant_ttc'),
            'factures_fournisseurs'  => (float) (clone $facturesFournisseurs)->sum('montant_ttc'),
            'impayees'               => (float) $this->model
                ->whereIn('statut', ['emise', 'partiellement_payee'])
                ->sum('net_a_payer'),
            'encaisse_mois'          => (float) $this->model
                ->where('statut', 'payee')
                ->whereMonth('date_facture', now()->month)
                ->sum('montant_paye'),
            'en_retard_count'        => $this->enRetard()->count(),
            'en_retard_montant'      => (float) $this->model->enRetard()->sum('net_a_payer'),
            'total_clients_count'    => (clone $facturesClients)->count(),
            'total_fournisseurs_count' => (clone $facturesFournisseurs)->count(),
        ];
    }

    public function chiffreAffaire(string $debut, string $fin): float
    {
        return (float) $this->model
            ->where('type', 'client')
            ->where('statut', '!=', 'annulee')
            ->whereBetween('date_facture', [$debut, $fin])
            ->sum('montant_ht');
    }

    public function encaisse(string $debut, string $fin): float
    {
        return (float) $this->model
            ->where('type', 'client')
            ->whereBetween('date_facture', [$debut, $fin])
            ->sum('montant_paye');
    }

    public function genererNumero(): string
    {
        $prefix = 'FAC';
        $last = $this->model->whereYear('created_at', now()->year)->max('id') ?? 0;
        return sprintf('%s-%s-%04d', $prefix, now()->year, $last + 1);
    }
}