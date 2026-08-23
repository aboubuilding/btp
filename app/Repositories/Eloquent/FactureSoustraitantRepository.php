<?php

namespace App\Repositories\Eloquent;

use App\Models\Facture;
use App\Repositories\Interfaces\FactureRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

abstract class FactureSoustraitantRepository extends BaseRepository implements FactureRepositoryInterface
{
    public function model(): string
    {
        return Facture::class;
    }

    public function getFacturesWithRelations(): array
    {
        return $this->activeQuery()
            ->with(['facturable', 'projet'])
            ->where('type', 'fournisseur')
            ->where('type_facturable', 'Soustraitant')
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    public function getFacturesBySoustraitant(int $soustraitantId): array
    {
        return $this->activeQuery()
            ->with(['projet'])
            ->where('type', 'fournisseur')
            ->where('type_facturable', 'Soustraitant')
            ->where('id_facturable', $soustraitantId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    public function getFacturesByProjet(int $projetId): array
    {
        return $this->activeQuery()
            ->with(['facturable'])
            ->where('type', 'fournisseur')
            ->where('type_facturable', 'Soustraitant')
            ->where('projet_id', $projetId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    public function getFacturesEnRetard(): array
    {
        return $this->activeQuery()
            ->with(['facturable', 'projet'])
            ->where('type', 'fournisseur')
            ->where('type_facturable', 'Soustraitant')
            ->where(function ($query) {
                $query->where('statut', 'en_retard')
                    ->orWhere(function ($q) {
                        $q->where('statut', 'emise')
                            ->whereNotNull('date_echeance')
                            ->where('date_echeance', '<', Carbon::now());
                    });
            })
            ->orderBy('date_echeance', 'asc')
            ->get()
            ->toArray();
    }

    public function updateStatus(int $id, string $status): bool
    {
        $facture = $this->find($id);
        if (!$facture) {
            return false;
        }
        $facture->statut = $status;
        return $facture->save();
    }

    public function search(string $keyword): array
    {
        return $this->activeQuery()
            ->with(['facturable', 'projet'])
            ->where('type', 'fournisseur')
            ->where('type_facturable', 'Soustraitant')
            ->where(function ($query) use ($keyword) {
                $query->where('numero_facture', 'LIKE', "%{$keyword}%")
                    ->orWhereHas('facturable', function ($q) use ($keyword) {
                        $q->where('nom_entreprise', 'LIKE', "%{$keyword}%")
                            ->orWhere('personne_contact', 'LIKE', "%{$keyword}%");
                    })
                    ->orWhereHas('projet', function ($q) use ($keyword) {
                        $q->where('nom', 'LIKE', "%{$keyword}%")
                            ->orWhere('code', 'LIKE', "%{$keyword}%");
                    });
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    public function getStats(): array
    {
        $total = $this->activeQuery()
            ->where('type', 'fournisseur')
            ->where('type_facturable', 'Soustraitant')
            ->count();

        $emises = $this->activeQuery()
            ->where('type', 'fournisseur')
            ->where('type_facturable', 'Soustraitant')
            ->where('statut', 'emise')
            ->count();

        $payees = $this->activeQuery()
            ->where('type', 'fournisseur')
            ->where('type_facturable', 'Soustraitant')
            ->where('statut', 'payee')
            ->count();

        $partiellement = $this->activeQuery()
            ->where('type', 'fournisseur')
            ->where('type_facturable', 'Soustraitant')
            ->where('statut', 'partiellement_payee')
            ->count();

        $annulees = $this->activeQuery()
            ->where('type', 'fournisseur')
            ->where('type_facturable', 'Soustraitant')
            ->where('statut', 'annulee')
            ->count();

        $enRetard = $this->activeQuery()
            ->where('type', 'fournisseur')
            ->where('type_facturable', 'Soustraitant')
            ->where('statut', 'en_retard')
            ->count();

        $montantTotal = $this->activeQuery()
            ->where('type', 'fournisseur')
            ->where('type_facturable', 'Soustraitant')
            ->sum('montant_ttc');

        $en_retard = 0;
        return [
            'total' => $total,
            'emises' => $emises,
            'payees' => $payees,
            'partiellement' => $partiellement,
            'annulees' => $annulees,
            'en_retard' => $en_retard,
            'montant_total' => $montantTotal,
        ];
    }

    public function generateNumeroFacture(): string
    {
        $year = date('Y');
        $month = date('m');
        $last = $this->activeQuery()
            ->where('type', 'fournisseur')
            ->where('type_facturable', 'Soustraitant')
            ->where('numero_facture', 'LIKE', "FAC-ST-{$year}{$month}-%")
            ->orderBy('numero_facture', 'desc')
            ->first();

        if ($last) {
            $lastNumber = intval(substr($last->numero_facture, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "FAC-ST-{$year}{$month}-{$newNumber}";
    }

    public function getFacturesImpayees(): array
    {
        return $this->activeQuery()
            ->with(['facturable'])
            ->where('type', 'fournisseur')
            ->where('type_facturable', 'Soustraitant')
            ->whereIn('statut', ['emise', 'partiellement_payee'])
            ->orderBy('date_echeance', 'asc')
            ->get()
            ->toArray();
    }

    public function getFacturesPayables(): array
    {
        return $this->activeQuery()
            ->with(['facturable'])
            ->where('type', 'fournisseur')
            ->where('type_facturable', 'Soustraitant')
            ->whereIn('statut', ['emise', 'partiellement_payee', 'en_retard'])
            ->whereRaw('montant_ttc > (SELECT COALESCE(SUM(montant), 0) FROM paiement_sous_traitants WHERE facture_sous_traitant_id = factures.id AND etat = 1)')
            ->orderBy('date_echeance', 'asc')
            ->get()
            ->toArray();
    }
}
