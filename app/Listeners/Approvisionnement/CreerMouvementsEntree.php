<?php
namespace App\Listeners\Approvisionnement;

use App\Domain\Approvisionnement\Events\LivraisonReceptionnee;
use App\Domain\Approvisionnement\Services\StockService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class CreerMouvementsEntree implements ShouldQueue
{
    public int $tries = 3;
    public string $queue = 'stock';

    public function __construct(private StockService $stock) {}

    public function handle(LivraisonReceptionnee $event): void
    {
        $livraison = $event->livraison;

        foreach ($livraison->articles as $article) {
            if ($article->quantite_recue <= 0) continue;

            try {
                $this->stock->entrer(
                    $livraison->entrepot_id,
                    $article->materiau_id,
                    (float) $article->quantite_recue,
                    (float) ($article->prix_unitaire ?? 0),
                    [
                        'reference_type' => get_class($livraison),
                        'reference_id'   => $livraison->id,
                    ]
                );
            } catch (\Throwable $e) {
                Log::error('[Listener] Échec création mouvement entrée', [
                    'livraison_id' => $livraison->id,
                    'article_id'   => $article->id,
                    'error'        => $e->getMessage(),
                ]);
            }
        }

        Log::info('[Listener] Mouvements d\'entrée créés', [
            'livraison_id' => $livraison->id,
            'nb_articles'  => $livraison->articles->count(),
        ]);
    }
}