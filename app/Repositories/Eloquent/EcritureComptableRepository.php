<?php

namespace App\Repositories\Eloquent;

use App\Models\EcritureComptable;
use App\Repositories\Interfaces\EcritureComptableRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EcritureComptableRepository extends BaseRepository implements EcritureComptableRepositoryInterface
{
    public function model(): string
    {
        return EcritureComptable::class;
    }

    public function getEcrituresWithRelations(): array
    {
        return $this->withSupprime()
            ->with(['exerciceFiscal', 'createur'])
            ->withCount('lignes')
            ->orderBy('date_ecriture', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    public function getEcrituresByExercice(int $exerciceId): array
    {
        return $this->activeQuery()
            ->with(['createur'])
            ->withCount('lignes')
            ->where('exercice_fiscal_id', $exerciceId)
            ->orderBy('date_ecriture', 'desc')
            ->get()
            ->toArray();
    }

    public function getEcrituresByStatut(string $statut): array
    {
        return $this->activeQuery()
            ->with(['exerciceFiscal', 'createur'])
            ->withCount('lignes')
            ->where('statut', $statut)
            ->orderBy('date_ecriture', 'desc')
            ->get()
            ->toArray();
    }

    public function getEcrituresByDateRange(string $start, string $end): array
    {
        return $this->activeQuery()
            ->with(['exerciceFiscal', 'createur'])
            ->withCount('lignes')
            ->whereBetween('date_ecriture', [$start, $end])
            ->orderBy('date_ecriture', 'asc')
            ->get()
            ->toArray();
    }

    public function updateStatut(int $id, string $statut): bool
    {
        $ecriture = $this->find($id);
        if (!$ecriture) {
            return false;
        }
        $ecriture->statut = $statut;
        return $ecriture->save();
    }

    public function search(string $keyword): array
    {
        return $this->withSupprime()
            ->with(['exerciceFiscal', 'createur'])
            ->withCount('lignes')
            ->where(function ($query) use ($keyword) {
                $query->where('numero_ecriture', 'LIKE', "%{$keyword}%")
                    ->orWhere('description', 'LIKE', "%{$keyword}%")
                    ->orWhere('type_reference', 'LIKE', "%{$keyword}%");
            })
            ->orderBy('date_ecriture', 'desc')
            ->get()
            ->toArray();
    }

    public function getStats(): array
    {
        $total = $this->withSupprime()->count();
        $brouillons = $this->activeQuery()->where('statut', 'brouillon')->count();
        $validees = $this->activeQuery()->where('statut', 'valide')->count();

        $totalDebit = DB::table('ligne_ecriture_comptables')
            ->join('ecriture_comptables', 'ligne_ecriture_comptables.ecriture_comptable_id', '=', 'ecriture_comptables.id')
            ->where('ecriture_comptables.etat', 1)
            ->sum('ligne_ecriture_comptables.debit');

        $totalCredit = DB::table('ligne_ecriture_comptables')
            ->join('ecriture_comptables', 'ligne_ecriture_comptables.ecriture_comptable_id', '=', 'ecriture_comptables.id')
            ->where('ecriture_comptables.etat', 1)
            ->sum('ligne_ecriture_comptables.credit');

        $parMois = $this->activeQuery()
            ->select(DB::raw('DATE_FORMAT(date_ecriture, "%Y-%m") as mois'), DB::raw('count(*) as total'))
            ->groupBy('mois')
            ->orderBy('mois', 'desc')
            ->limit(6)
            ->get()
            ->toArray();

        return [
            'total' => $total,
            'brouillons' => $brouillons,
            'validees' => $validees,
            'total_debit' => $totalDebit,
            'total_credit' => $totalCredit,
            'par_mois' => $parMois,
        ];
    }

    public function generateNumero(): string
    {
        return EcritureComptable::generateNumero();
    }
}
