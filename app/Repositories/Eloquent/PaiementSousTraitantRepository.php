<?php

namespace App\Repositories\Eloquent;

use App\Models\PaiementSousTraitant;
use App\Repositories\Interfaces\PaiementSousTraitantRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PaiementSousTraitantRepository extends BaseRepository implements PaiementSousTraitantRepositoryInterface
{
    public function model(): string
    {
        return PaiementSousTraitant::class;
    }

    public function getPaiementsWithRelations(): array
    {
        return $this->activeQuery()
            ->with(['facture', 'facture.facturable'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    public function getPaiementsByFacture(int $factureId): array
    {
        return $this->activeQuery()
            ->where('facture_sous_traitant_id', $factureId)
            ->orderBy('date_paiement', 'desc')
            ->get()
            ->toArray();
    }

    public function getPaiementsBySoustraitant(int $soustraitantId): array
    {
        return $this->activeQuery()
            ->whereHas('facture', function ($query) use ($soustraitantId) {
                $query->where('id_facturable', $soustraitantId)
                    ->where('type_facturable', 'Soustraitant');
            })
            ->with(['facture'])
            ->orderBy('date_paiement', 'desc')
            ->get()
            ->toArray();
    }

    public function search(string $keyword): array
    {
        return $this->activeQuery()
            ->with(['facture', 'facture.facturable'])
            ->where(function ($query) use ($keyword) {
                $query->where('reference', 'LIKE', "%{$keyword}%")
                    ->orWhere('mode', 'LIKE', "%{$keyword}%")
                    ->orWhere('montant', 'LIKE', "%{$keyword}%")
                    ->orWhereHas('facture', function ($q) use ($keyword) {
                        $q->where('numero_facture', 'LIKE', "%{$keyword}%")
                            ->orWhereHas('facturable', function ($sub) use ($keyword) {
                                $sub->where('nom_entreprise', 'LIKE', "%{$keyword}%");
                            });
                    });
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    public function getStats(): array
    {
        $total = $this->activeQuery()->count();
        $montantTotal = $this->activeQuery()->sum('montant');

        $parMode = $this->activeQuery()
            ->select('mode', DB::raw('count(*) as total'), DB::raw('sum(montant) as montant_total'))
            ->groupBy('mode')
            ->get()
            ->toArray();

        $parMois = $this->activeQuery()
            ->select(DB::raw('DATE_FORMAT(date_paiement, "%Y-%m") as mois'), DB::raw('sum(montant) as total'))
            ->groupBy('mois')
            ->orderBy('mois', 'desc')
            ->limit(6)
            ->get()
            ->toArray();

        return [
            'total' => $total,
            'montant_total' => $montantTotal,
            'par_mode' => $parMode,
            'par_mois' => $parMois,
        ];
    }

    public function getTotalPaiementsByFacture(int $factureId): float
    {
        return $this->activeQuery()
            ->where('facture_sous_traitant_id', $factureId)
            ->sum('montant');
    }
}
