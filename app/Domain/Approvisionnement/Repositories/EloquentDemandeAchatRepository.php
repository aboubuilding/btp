<?php
namespace App\Domain\Approvisionnement\Repositories;

use App\Domain\Approvisionnement\Models\DemandeAchat;
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentDemandeAchatRepository extends BaseRepository implements DemandeAchatRepositoryInterface
{
    protected array $with = ['projet', 'demandeur'];
    protected array $colonnesSearch = ['numero'];
    protected array $filtresSimples = ['statut', 'projet_id', 'demandeur_id'];
    protected string $orderBy = 'date_demande';

    public function __construct(DemandeAchat $model)
    {
        parent::__construct($model);
    }

    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->paginate($filtres, $parPage);
    }

    public function avecArticles(DemandeAchat $demande): DemandeAchat
    {
        return $demande->load(['projet', 'demandeur', 'articles.materiau', 'bonsCommande']);
    }

    public function enAttente(): Collection
    {
        return $this->newQuery()->where('statut', 'en_attente')->get();
    }

    public function parProjet(int $projetId): Collection
    {
        return $this->newQuery()->where('projet_id', $projetId)->latest()->get();
    }

    public function statistiques(): array
    {
        return [
            'total'       => $this->model->count(),
            'en_attente'  => $this->model->where('statut', 'en_attente')->count(),
            'validees'    => $this->model->where('statut', 'validee')->count(),
            'rejetees'    => $this->model->where('statut', 'rejetee')->count(),
            'commandees'  => $this->model->where('statut', 'commandee')->count(),
        ];
    }

    public function genererNumero(): string
    {
        $prefix = 'DA';
        $last = $this->model->whereYear('created_at', now()->year)->max('id') ?? 0;
        return sprintf('%s-%s-%04d', $prefix, now()->year, $last + 1);
    }
}