<?php
namespace App\Domain\Socle\Repositories;

use App\Domain\Socle\Models\Communication;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentCommunicationRepository extends BaseRepository implements CommunicationRepositoryInterface
{
    protected array $with = ['expediteur', 'communicable'];
    protected array $colonnesSearch = ['sujet', 'corps'];
    protected array $filtresSimples = ['statut', 'type'];
    protected string $orderBy = 'created_at';

    public function __construct(Communication $model)
    {
        parent::__construct($model);
    }

    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->paginate($filtres, $parPage);
    }

    public function aEnvoyer(): Collection
    {
        return $this->newQuery()
            ->where('statut', 'brouillon')
            ->whereNotNull('planifie_le')
            ->where('planifie_le', '<=', now())
            ->where('etat', 1)
            ->get();
    }

    public function brouillons(): Collection
    {
        return $this->newQuery()->where('statut', 'brouillon')->get();
    }

    public function echoues(): Collection
    {
        return $this->newQuery()->where('statut', 'echoue')->get();
    }

    public function statistiques(): array
    {
        return [
            'envoyes'    => $this->model->where('statut', 'envoye')->count(),
            'brouillons' => $this->model->where('statut', 'brouillon')->count(),
            'echecs'     => $this->model->where('statut', 'echoue')->count(),
            'mois'       => $this->model->whereMonth('created_at', now()->month)->count(),
            'total'      => $this->model->count(),
        ];
    }

    public function pourObjet(string $type, int $id): Collection
    {
        return $this->newQuery()
            ->where('communicable_type', $type)
            ->where('communicable_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function marquerEnvoye(Communication $comm, ?string $erreur = null): bool
    {
        if ($erreur) {
            return $comm->update(['statut' => 'echoue', 'erreur' => $erreur]);
        }
        return $comm->update(['statut' => 'envoye', 'envoye_le' => now(), 'erreur' => null]);
    }
}