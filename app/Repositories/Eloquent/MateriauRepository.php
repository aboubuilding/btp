<?php

namespace App\Repositories\Eloquent;

use App\Models\Materiau;
use App\Repositories\Interfaces\MateriauRepositoryInterface;
use Illuminate\Support\Facades\DB;

class MateriauRepository extends BaseRepository implements MateriauRepositoryInterface
{
    public function model(): string
    {
        return Materiau::class;
    }

    public function getAlertesStock(): array
    {
        return DB::table('materiaux')
            ->join('niveaux_stock', 'materiaux.id', '=', 'niveaux_stock.materiau_id')
            ->join('entrepots', 'niveaux_stock.entrepot_id', '=', 'entrepots.id')
            ->select(
                'materiaux.nom as materiau_nom',
                'materiaux.code',
                'materiaux.unite',
                'materiaux.seuil_alerte_stock_min',
                'niveaux_stock.quantite',
                'entrepots.nom as entrepot_nom'
            )
            ->where('materiaux.etat', 1)
            ->where('niveaux_stock.quantite', '<', DB::raw('materiaux.seuil_alerte_stock_min'))
            ->where('niveaux_stock.quantite', '>', 0)
            ->orderBy('niveaux_stock.quantite', 'asc')
            ->get()
            ->toArray();
    }

    public function getRupturesStock(): array
    {
        return DB::table('materiaux')
            ->join('niveaux_stock', 'materiaux.id', '=', 'niveaux_stock.materiau_id')
            ->join('entrepots', 'niveaux_stock.entrepot_id', '=', 'entrepots.id')
            ->select(
                'materiaux.nom as materiau_nom',
                'materiaux.code',
                'materiaux.unite',
                'entrepots.nom as entrepot_nom'
            )
            ->where('materiaux.etat', 1)
            ->where('niveaux_stock.quantite', 0)
            ->get()
            ->toArray();
    }

    public function getTotalAlertes(): int
    {
        return count($this->getAlertesStock());
    }

    public function getTotalRuptures(): int
    {
        return count($this->getRupturesStock());
    }

    public function getCritiqueStock(): int
    {
        return $this->getTotalAlertes() + $this->getTotalRuptures();
    }
}
