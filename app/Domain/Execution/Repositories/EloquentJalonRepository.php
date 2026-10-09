<?php
namespace App\Domain\Execution\Repositories;

use App\Domain\Execution\Models\JalonProjet;
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Support\Collection;

class EloquentJalonRepository extends BaseRepository implements JalonRepositoryInterface
{
    protected string $orderBy = 'date_echeance';
    protected string $orderDir = 'asc';

    public function __construct(JalonProjet $model)
    {
        parent::__construct($model);
    }

    public function parProjet(int $projetId): Collection
    {
        return $this->newQuery()->where('projet_id', $projetId)->orderBy('date_echeance')->get();
    }

    public function atteints(int $projetId): Collection
    {
        return $this->newQuery()
            ->where('projet_id', $projetId)
            ->whereNotNull('date_atteinte')
            ->get();
    }

    public function manques(): Collection
    {
        return $this->newQuery()->manques()->get();
    }

    public function prochainsEcheances(int $jours = 30): Collection
    {
        return $this->newQuery()
            ->whereNull('date_atteinte')
            ->whereBetween('date_echeance', [now(), now()->addDays($jours)])
            ->orderBy('date_echeance')
            ->get();
    }
}