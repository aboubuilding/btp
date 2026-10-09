<?php
namespace App\Domain\Approvisionnement\Repositories;

use App\Domain\Approvisionnement\Models\{NiveauStock, MouvementStock};
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentStockRepository implements StockRepositoryInterface
{
    public function niveauxAvecAlertes(array $filtres = []): Collection
    {
        return NiveauStock::query()
            ->with(['materiau.categorie', 'entrepot'])
            ->when(!empty($filtres['entrepot_id']), fn($q) => $q->where('entrepot_id', $filtres['entrepot_id']))
            ->when(!empty($filtres['search']), fn($q) => $q->whereHas('materiau', fn($qq) =>
                $qq->where('nom', 'like', "%{$filtres['search']}%")
                   ->orWhere('code', 'like', "%{$filtres['search']}%")
            ))
            ->when(!empty($filtres['categorie_id']), fn($q) => $q->whereHas('materiau', fn($qq) =>
                $qq->where('categorie_id', $filtres['categorie_id'])
            ))
            ->orderBy('quantite')
            ->get();
    }

    public function niveauxParEntrepot(int $entrepotId): Collection
    {
        return NiveauStock::with('materiau')
            ->where('entrepot_id', $entrepotId)
            ->get();
    }

    public function niveau(int $entrepotId, int $materiauId): ?NiveauStock
    {
        return NiveauStock::where('entrepot_id', $entrepotId)
            ->where('materiau_id', $materiauId)
            ->first();
    }

    public function sousSeuil(): Collection
    {
        return NiveauStock::with(['materiau', 'entrepot'])
            ->whereRaw('quantite <= (SELECT seuil_alerte_stock_min FROM materiaux WHERE materiaux.id = niveau_stocks.materiau_id)')
            ->get();
    }

    public function valeurTotale(): float
    {
        return (float) NiveauStock::sum(DB::raw('quantite * cmup'));
    }

    public function valeurParEntrepot(int $entrepotId): float
    {
        return (float) NiveauStock::where('entrepot_id', $entrepotId)
            ->sum(DB::raw('quantite * cmup'));
    }

    public function consommationParChantier(int $projetId): Collection
    {
        return MouvementStock::where('projet_id', $projetId)
            ->whereIn('type', ['sortie'])
            ->with('materiau')
            ->select('materiau_id',
                DB::raw('SUM(quantite) as total_quantite'),
                DB::raw('SUM(quantite * prix_unitaire) as total_cout'))
            ->groupBy('materiau_id')
            ->get();
    }

    public function dernierCmup(int $entrepotId, int $materiauId): float
    {
        return (float) (NiveauStock::where('entrepot_id', $entrepotId)
            ->where('materiau_id', $materiauId)
            ->value('cmup') ?? 0);
    }

    public function statistiques(): array
    {
        return [
            'valeur_totale'   => $this->valeurTotale(),
            'nb_articles'     => NiveauStock::count(),
            'sous_seuil'      => $this->sousSeuil()->count(),
            'mouvements_mois' => MouvementStock::whereMonth('date_mouvement', now()->month)->count(),
            'nb_depots'       => \App\Domain\Approvisionnement\Models\Entrepot::where('etat', 1)->count(),
        ];
    }
}