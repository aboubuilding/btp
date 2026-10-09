<?php
namespace App\Domain\Commercial\Repositories;

use App\Domain\Commercial\Models\Marche;
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentMarcheRepository extends BaseRepository implements MarcheRepositoryInterface
{
    protected array $with = ['client'];
    protected array $colonnesSearch = ['reference', 'objet'];
    protected array $filtresSimples = ['statut', 'client_id'];
    protected string $orderBy = 'date_signature';

    public function __construct(Marche $model)
    {
        parent::__construct($model);
    }

    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->paginate($filtres, $parPage);
    }

    public function avecDetails(Marche $marche): Marche
    {
        return $marche->load([
            'client', 'devis', 'avenants', 'cautions', 'projets',
        ]);
    }

    public function actifs(): Collection
    {
        return $this->newQuery()
            ->whereIn('statut', ['signe', 'en_cours'])
            ->get();
    }

    public function signes(): Collection
    {
        return $this->newQuery()->whereIn('statut', ['signe', 'en_cours', 'receptionne'])->get();
    }

    public function parClient(int $clientId): Collection
    {
        return $this->newQuery()->where('client_id', $clientId)->latest('date_signature')->get();
    }

    public function statistiques(): array
    {
        return [
            'total'          => $this->model->where('etat', 1)->count(),
            'brouillons'     => $this->model->where('statut', 'brouillon')->count(),
            'signes'         => $this->model->where('statut', 'signe')->count(),
            'en_cours'       => $this->model->where('statut', 'en_cours')->count(),
            'receptionnes'   => $this->model->where('statut', 'receptionne')->count(),
            'clos'           => $this->model->where('statut', 'clos')->count(),
        ];
    }

    public function montantTotalPortefeuille(): float
    {
        return (float) $this->model->where('etat', 1)
            ->whereIn('statut', ['signe', 'en_cours', 'receptionne'])
            ->sum('montant_initial');
    }

    public function genererReference(): string
    {
        $prefix = 'MAR';
        $last = $this->model->whereYear('created_at', now()->year)->max('id') ?? 0;
        return sprintf('%s-%s-%04d', $prefix, now()->year, $last + 1);
    }
}