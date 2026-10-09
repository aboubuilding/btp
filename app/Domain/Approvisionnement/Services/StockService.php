<?php
namespace App\Domain\Approvisionnement\Services;

use App\Domain\Approvisionnement\Models\{NiveauStock, MouvementStock, Materiau};
use App\Domain\Approvisionnement\Repositories\StockRepositoryInterface;
use App\Domain\Approvisionnement\Events\{StockSousSeuil, SortieStockEnregistree};
use App\Domain\Socle\Exceptions\RegleGestionException;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StockService
{
    public function __construct(
        private StockRepositoryInterface $repo,
        private JournalService $journal,
    ) {}

    /**
     * RG-S01 : entrée avec recalcul du CMUP.
     */
    public function entrer(
        int $entrepotId,
        int $materiauId,
        float $quantite,
        float $prixUnitaire,
        array $ref = []
    ): MouvementStock {
        if ($quantite <= 0) {
            throw new RegleGestionException('Quantité invalide.');
        }

        return DB::transaction(function () use ($entrepotId, $materiauId, $quantite, $prixUnitaire, $ref) {
            $niveau = NiveauStock::lockForUpdate()->firstOrCreate(
                ['entrepot_id' => $entrepotId, 'materiau_id' => $materiauId],
                ['quantite' => 0, 'cmup' => 0]
            );

            $qAncienne = (float) $niveau->quantite;
            $cmupAncien = (float) $niveau->cmup;

            $nouveauCmup = ($qAncienne + $quantite) > 0
                ? round((($qAncienne * $cmupAncien) + ($quantite * $prixUnitaire)) / ($qAncienne + $quantite), 2)
                : $prixUnitaire;

            $niveau->update([
                'quantite' => $qAncienne + $quantite,
                'cmup'     => $nouveauCmup,
            ]);

            $mouvement = MouvementStock::create(array_merge([
                'entrepot_id'    => $entrepotId,
                'materiau_id'    => $materiauId,
                'type'           => 'entree',
                'quantite'       => $quantite,
                'prix_unitaire'  => $prixUnitaire,
                'date_mouvement' => now()->toDateString(),
                'enregistre_par' => auth()->id(),
            ], $ref));

            $this->journal->log('stock.entree', $mouvement, null, null, [
                'entrepot_id' => $entrepotId,
                'materiau_id' => $materiauId,
                'quantite'    => $quantite,
                'cmup'        => $nouveauCmup,
            ]);

            return $mouvement;
        });
    }

    /**
     * RG-S02, RG-S03 : sortie obligatoirement imputée à un chantier.
     */
    public function sortir(
        int $entrepotId,
        int $materiauId,
        float $quantite,
        ?int $projetId,
        array $ref = []
    ): MouvementStock {
        if ($quantite <= 0) {
            throw new RegleGestionException('Quantité invalide.');
        }
        if (!$projetId) {
            throw new RegleGestionException('Toute sortie doit être imputée à un chantier (RG-S03).');
        }

        return DB::transaction(function () use ($entrepotId, $materiauId, $quantite, $projetId, $ref) {
            $niveau = NiveauStock::where('entrepot_id', $entrepotId)
                ->where('materiau_id', $materiauId)
                ->lockForUpdate()
                ->first();

            if (!$niveau || (float) $niveau->quantite < $quantite) {
                throw new RegleGestionException(
                    'Stock insuffisant (RG-S02). Disponible : ' . ($niveau->quantite ?? 0)
                );
            }

            $niveau->decrement('quantite', $quantite);

            $mouvement = MouvementStock::create(array_merge([
                'entrepot_id'    => $entrepotId,
                'materiau_id'    => $materiauId,
                'type'           => 'sortie',
                'quantite'       => $quantite,
                'prix_unitaire'  => $niveau->cmup,
                'projet_id'      => $projetId,
                'date_mouvement' => now()->toDateString(),
                'enregistre_par' => auth()->id(),
            ], $ref));

            // RG-S05 : alerte seuil
            $materiau = Materiau::find($materiauId);
            $niveauFresh = $niveau->fresh();
            if ($materiau && (float) $niveauFresh->quantite <= (float) $materiau->seuil_alerte_stock_min) {
                event(new StockSousSeuil($materiau, $niveauFresh));
            }

            event(new SortieStockEnregistree($mouvement));
            $this->journal->log('stock.sortie', $mouvement, null, null, [
                'projet_id' => $projetId,
                'quantite'  => $quantite,
            ]);

            return $mouvement;
        });
    }

    /**
     * RG-S04 : transfert = 2 mouvements en une transaction.
     */
    public function transferer(int $sourceId, int $destId, int $materiauId, float $quantite): void
    {
        if ($sourceId === $destId) {
            throw new RegleGestionException('Les dépôts source et destination doivent différer.');
        }

        DB::transaction(function () use ($sourceId, $destId, $materiauId, $quantite) {
            $niveauSource = NiveauStock::where('entrepot_id', $sourceId)
                ->where('materiau_id', $materiauId)
                ->lockForUpdate()
                ->first();

            if (!$niveauSource || (float) $niveauSource->quantite < $quantite) {
                throw new RegleGestionException('Stock source insuffisant.');
            }

            $cmupSource = (float) $niveauSource->cmup;

            $this->sortir($sourceId, $materiauId, $quantite, null, ['type' => 'transfert_sortie']);
            $this->entrer($destId, $materiauId, $quantite, $cmupSource, ['type' => 'transfert_entree']);

            $this->journal->log('stock.transfert', null, null, null, [
                'source'      => $sourceId,
                'destination' => $destId,
                'materiau'    => $materiauId,
                'quantite'    => $quantite,
            ]);
        });
    }

    public function niveau(int $entrepotId, int $materiauId): ?NiveauStock
    {
        return $this->repo->niveau($entrepotId, $materiauId);
    }

    public function sousSeuil(): Collection
    {
        return $this->repo->sousSeuil();
    }

    public function valeurTotale(): float
    {
        return $this->repo->valeurTotale();
    }

    public function statistiques(): array
    {
        return $this->repo->statistiques();
    }
}