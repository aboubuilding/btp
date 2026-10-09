<?php
namespace App\Domain\Execution\Repositories;

use App\Domain\Execution\Models\Situation;
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentSituationRepository extends BaseRepository implements SituationRepositoryInterface
{
    protected array $with = ['projet'];
    protected array $filtresSimples = ['projet_id', 'statut'];
    protected string $orderBy = 'periode_debut';

    public function __construct(Situation $model)
    {
        parent::__construct($model);
    }

    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->paginate($filtres, $parPage);
    }

    public function avecDetails(Situation $situation): Situation
    {
        return $situation->load([
            'projet.client', 'projet.marche',
            'attachement.lignes.ligneDevis', 'attachement.etabliPar',
            'validePar', 'facture',
        ]);
    }

    public function parProjet(int $projetId): Collection
    {
        return $this->newQuery()->where('projet_id', $projetId)->orderBy('numero')->get();
    }

    public function dernierePourProjet(int $projetId): ?Situation
    {
        return $this->newQuery()
            ->where('projet_id', $projetId)
            ->orderByDesc('numero')
            ->first();
    }

    public function prochainNumero(int $projetId): int
    {
        return (int) ($this->model->where('projet_id', $projetId)->max('numero') ?? 0) + 1;
    }

    public function approuveesNonFacturees(): Collection
    {
        return $this->newQuery()
            ->where('statut', 'approuvee')
            ->whereDoesntHave('facture')
            ->get();
    }

    public function statistiques(): array
    {
        return [
            'total'           => $this->model->where('etat', 1)->count(),
            'brouillons'      => $this->model->where('statut', 'brouillon')->count(),
            'validees'        => $this->model->where('statut', 'validee')->count(),
            'approuvees'      => $this->model->where('statut', 'approuvee')->count(),
            'facturees'       => $this->model->where('statut', 'facturee')->count(),
            'montant_periode' => (float) $this->model->whereYear('periode_debut', now()->year)
                ->sum('montant_periode_ht'),
            'net_a_payer'     => (float) $this->model->whereYear('periode_debut', now()->year)
                ->sum('net_a_payer'),
        ];
    }

    public function chiffreAffaireFacture(?int $projetId = null): float
    {
        $q = $this->model->where('statut', 'facturee');
        if ($projetId) $q->where('projet_id', $projetId);
        return (float) $q->sum('montant_periode_ht');
    }
}