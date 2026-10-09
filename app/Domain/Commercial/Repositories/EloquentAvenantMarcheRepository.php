<?php
namespace App\Domain\Commercial\Repositories;

use App\Domain\Commercial\Models\AvenantMarche;
use App\Domain\Socle\Repositories\BaseRepository;
use Illuminate\Support\Collection;

class EloquentAvenantMarcheRepository extends BaseRepository implements AvenantMarcheRepositoryInterface
{
    public function __construct(AvenantMarche $model)
    {
        parent::__construct($model);
    }

    public function parMarche(int $marcheId): Collection
    {
        return $this->newQuery()->where('marche_id', $marcheId)->latest('date_signature')->get();
    }

    public function signesParMarche(int $marcheId): Collection
    {
        return $this->newQuery()
            ->where('marche_id', $marcheId)
            ->where('est_signe', true)
            ->get();
    }

    public function montantSignesParMarche(int $marcheId): float
    {
        return (float) $this->model
            ->where('marche_id', $marcheId)
            ->where('est_signe', true)
            ->sum('montant');
    }

    public function genererNumero(int $marcheId): string
    {
        $count = $this->model->where('marche_id', $marcheId)->count();
        return 'AV-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
    }
}