<?php
namespace App\Domain\Commercial\Repositories;

use App\Domain\Commercial\Models\CautionMarche;
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Support\Collection;

class EloquentCautionMarcheRepository extends BaseRepository implements CautionMarcheRepositoryInterface
{
    public function __construct(CautionMarche $model)
    {
        parent::__construct($model);
    }

    public function parMarche(int $marcheId): Collection
    {
        return $this->newQuery()->where('marche_id', $marcheId)->get();
    }

    public function activesParMarche(int $marcheId): Collection
    {
        return $this->newQuery()
            ->where('marche_id', $marcheId)
            ->where('statut', 'active')
            ->get();
    }

    public function expirentBientot(int $jours = 30): Collection
    {
        return $this->newQuery()
            ->where('statut', 'active')
            ->whereNotNull('date_echeance')
            ->whereBetween('date_echeance', [now(), now()->addDays($jours)])
            ->get();
    }

    public function montantTotalActif(int $marcheId): float
    {
        return (float) $this->model
            ->where('marche_id', $marcheId)
            ->where('statut', 'active')
            ->sum('montant');
    }
}