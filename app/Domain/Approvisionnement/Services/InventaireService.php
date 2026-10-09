<?php
namespace App\Domain\Approvisionnement\Services;

use App\Domain\Approvisionnement\Models\{Inventaire, NiveauStock};
use App\Domain\Socle\Exceptions\RegleGestionException;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Support\Facades\DB;

class InventaireService
{
    public function __construct(
        private StockService $stockService,
        private JournalService $journal,
    ) {}

    public function creer(array $data): Inventaire
    {
        return DB::transaction(function () use ($data) {
            $inventaire = Inventaire::create(array_merge($data, ['statut' => 'brouillon']));

            $niveaux = NiveauStock::where('entrepot_id', $data['entrepot_id'])
                ->with('materiau')
                ->get();

            foreach ($niveaux as $niveau) {
                $inventaire->articles()->create([
                    'materiau_id'        => $niveau->materiau_id,
                    'quantite_theorique' => $niveau->quantite,
                    'quantite_comptee'   => $niveau->quantite,
                    'ecart'              => 0,
                ]);
            }

            $this->journal->log('inventaire.cree', $inventaire);
            return $inventaire->fresh(['articles']);
        });
    }

    public function mettreAJourArticle(Inventaire $inventaire, int $articleId, float $quantiteComptee): void
    {
        if ($inventaire->statut === 'valide') {
            throw new RegleGestionException('Inventaire déjà validé.');
        }

        $article = $inventaire->articles()->findOrFail($articleId);
        $article->update([
            'quantite_comptee' => $quantiteComptee,
            'ecart'            => $quantiteComptee - (float) $article->quantite_theorique,
        ]);
    }

    public function valider(Inventaire $inventaire, int $userId): Inventaire
    {
        if ($inventaire->statut === 'valide') {
            throw new RegleGestionException('Inventaire déjà validé.');
        }

        return DB::transaction(function () use ($inventaire, $userId) {
            foreach ($inventaire->articles as $article) {
                if ((float) $article->ecart !== 0.0) {
                    $mouvement = $this->stockService->entrer(
                        $inventaire->entrepot_id,
                        $article->materiau_id,
                        (float) $article->ecart,
                        $article->materiau->prix_unitaire ?? 0,
                        ['type' => 'ajustement', 'reference_type' => Inventaire::class, 'reference_id' => $inventaire->id]
                    );
                }
            }

            $inventaire->update([
                'statut'     => 'valide',
                'valide_par' => $userId,
                'valide_le'  => now(),
            ]);

            $this->journal->log('inventaire.valide', $inventaire);
            return $inventaire;
        });
    }
}