<?php

namespace App\Repositories\Eloquent;

use App\Models\LigneBudget;
use App\Repositories\Interfaces\LigneBudgetRepositoryInterface;
use Illuminate\Support\Facades\DB;

class LigneBudgetRepository extends BaseRepository implements LigneBudgetRepositoryInterface
{
    public function model(): string
    {
        return LigneBudget::class;
    }

    public function getBudgetByCategorie(): array
    {
        return DB::table('lignes_budget_projet')
            ->join('projets', 'lignes_budget_projet.projet_id', '=', 'projets.id')
            ->select(
                'lignes_budget_projet.categorie',
                DB::raw('SUM(lignes_budget_projet.montant_prevue) as total_prevue'),
                DB::raw('SUM(lignes_budget_projet.montant_reel) as total_reel')
            )
            ->where('projets.etat', 1)
            ->groupBy('lignes_budget_projet.categorie')
            ->get()
            ->toArray();
    }
}
