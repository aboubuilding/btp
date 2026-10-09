<?php
namespace App\Domain\Execution\Repositories;

use App\Domain\Execution\Models\PhaseProjet;
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Support\Collection;

class EloquentPhaseRepository extends BaseRepository implements PhaseRepositoryInterface
{
    protected array $with = ['taches'];
    protected string $orderBy = 'ordre';
    protected string $orderDir = 'asc';

    public function __construct(PhaseProjet $model)
    {
        parent::__construct($model);
    }

    public function parProjet(int $projetId): Collection
    {
        return $this->newQuery()
            ->where('projet_id', $projetId)
            ->orderBy('ordre')
            ->get();
    }

    public function ordonnees(int $projetId): Collection
    {
        return $this->parProjet($projetId);
    }

    public function avecTaches(int $projetId): Collection
    {
        return $this->newQuery()
            ->with(['taches.assigne'])
            ->where('projet_id', $projetId)
            ->orderBy('ordre')
            ->get();
    }
}