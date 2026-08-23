<?php

namespace App\Repositories\Eloquent;

use App\Models\Facture;
use App\Repositories\Interfaces\FactureRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FactureRepository extends BaseRepository implements FactureRepositoryInterface
{
    public function model(): string
    {
        return Facture::class;
    }

    public function getFacturesEnRetard(): array
    {
        return DB::table('factures')
            ->join('clients', 'factures.client_id', '=', 'clients.id')
            ->join('projets', 'factures.projet_id', '=', 'projets.id')
            ->select(
                'factures.*',
                'clients.nom as client_nom',
                'projets.nom as projet_nom',
                'projets.code as projet_code'
            )
            ->where('factures.etat', 1)
            ->where('factures.type', 'client')
            ->where('factures.date_echeance', '<', Carbon::now())
            ->whereNotIn('factures.statut', ['payee', 'annulee'])
            ->orderBy('factures.date_echeance', 'asc')
            ->get()
            ->toArray();
    }

    public function getTotalMontantRetard(): float
    {
        return DB::table('factures')
            ->where('etat', 1)
            ->where('type', 'client')
            ->where('date_echeance', '<', Carbon::now())
            ->whereNotIn('statut', ['payee', 'annulee'])
            ->sum('montant_ttc');
    }

    public function getNombreFacturesRetard(): int
    {
        return DB::table('factures')
            ->where('etat', 1)
            ->where('type', 'client')
            ->where('date_echeance', '<', Carbon::now())
            ->whereNotIn('statut', ['payee', 'annulee'])
            ->count();
    }

    public function getClientsConcernes(): int
    {
        $clients = DB::table('factures')
            ->where('etat', 1)
            ->where('type', 'client')
            ->where('date_echeance', '<', Carbon::now())
            ->whereNotIn('statut', ['payee', 'annulee'])
            ->distinct('client_id')
            ->pluck('client_id');

        return $clients->count();
    }

    public function getFacturesProchesEcheance(int $days = 3): array
    {
        $dateProche = Carbon::now()->addDays($days);
        return DB::table('factures')
            ->join('clients', 'factures.client_id', '=', 'clients.id')
            ->select(
                'factures.*',
                'clients.nom as client_nom'
            )
            ->where('factures.etat', 1)
            ->where('factures.type', 'client')
            ->whereBetween('factures.date_echeance', [Carbon::now(), $dateProche])
            ->whereNotIn('factures.statut', ['payee', 'annulee'])
            ->orderBy('factures.date_echeance', 'asc')
            ->limit(5)
            ->get()
            ->toArray();
    }

    public function getFacturesByMonth(int $year, int $month): float
    {
        return DB::table('factures')
            ->where('etat', 1)
            ->where('type', 'client')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('montant_ttc');
    }
}
