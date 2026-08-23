<?php

namespace App\Repositories\Eloquent;

use App\Models\LigneEcritureComptable;
use App\Repositories\Interfaces\LigneEcritureComptableRepositoryInterface;
use Illuminate\Support\Facades\DB;

class LigneEcritureComptableRepository extends BaseRepository implements LigneEcritureComptableRepositoryInterface
{
    public function model(): string
    {
        return LigneEcritureComptable::class;
    }

    public function getLignesByEcriture(int $ecritureId): array
    {
        return $this->activeQuery()
            ->with(['compte'])
            ->where('ecriture_comptable_id', $ecritureId)
            ->orderBy('id')
            ->get()
            ->toArray();
    }

    public function getTotalByEcriture(int $ecritureId): array
    {
        $totals = $this->activeQuery()
            ->where('ecriture_comptable_id', $ecritureId)
            ->select(
                DB::raw('SUM(debit) as total_debit'),
                DB::raw('SUM(credit) as total_credit')
            )
            ->first();

        return [
            'total_debit' => $totals->total_debit ?? 0,
            'total_credit' => $totals->total_credit ?? 0,
        ];
    }

    public function getSoldeByCompte(int $compteId, ?int $exerciceId = null): float
    {
        $query = $this->activeQuery()
            ->join('ecriture_comptables', 'ligne_ecriture_comptables.ecriture_comptable_id', '=', 'ecriture_comptables.id')
            ->where('ligne_ecriture_comptables.compte_id', $compteId)
            ->where('ecriture_comptables.statut', 'valide');

        if ($exerciceId) {
            $query->where('ecriture_comptables.exercice_fiscal_id', $exerciceId);
        }

        $totals = $query->select(
            DB::raw('SUM(debit) as total_debit'),
            DB::raw('SUM(credit) as total_credit')
        )->first();

        return ($totals->total_debit ?? 0) - ($totals->total_credit ?? 0);
    }
}
