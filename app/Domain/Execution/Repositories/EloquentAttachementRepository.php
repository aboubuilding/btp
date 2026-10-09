<?php
namespace App\Domain\Execution\Repositories;

use App\Domain\Execution\Models\Attachement;
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentAttachementRepository extends BaseRepository implements AttachementRepositoryInterface
{
    protected array $with = ['projet', 'etabliPar'];
    protected array $filtresSimples = ['projet_id', 'statut'];
    protected string $orderBy = 'periode_debut';

    public function __construct(Attachement $model)
    {
        parent::__construct($model);
    }

    public function paginateAvecRelations(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->paginate($filtres, $parPage);
    }

    public function avecLignes(Attachement $attachement): Attachement
    {
        return $attachement->load(['projet', 'etabliPar', 'lignes.ligneDevis', 'situation']);
    }

    public function parProjet(int $projetId): Collection
    {
        return $this->newQuery()->where('projet_id', $projetId)->orderByDesc('numero')->get();
    }

    public function valides(int $projetId): Collection
    {
        return $this->newQuery()
            ->where('projet_id', $projetId)
            ->where('statut', 'valide')
            ->orderBy('periode_debut')
            ->get();
    }

    public function prochainNumero(int $projetId): int
    {
        return (int) ($this->model->where('projet_id', $projetId)->max('numero') ?? 0) + 1;
    }
}