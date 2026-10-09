<?php
namespace App\Listeners\Approvisionnement;

use App\Domain\Approvisionnement\Events\LivraisonReceptionnee;
use App\Domain\Approvisionnement\Models\NiveauStock;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecalculerCmup implements ShouldQueue
{
    public string $queue = 'stock';

    public function __construct(private JournalService $journal) {}

    public function handle(LivraisonReceptionnee $event): void
    {
        $livraison = $event->livraison;

        foreach ($livraison->articles as $article) {
            $niveau = NiveauStock::where('entrepot_id', $livraison->entrepot_id)
                ->where('materiau_id', $article->materiau_id)
                ->first();

            if ($niveau) {
                $this->journal->log('stock.cmup_recalcule', $niveau, null, null, [
                    'entrepot_id' => $livraison->entrepot_id,
                    'materiau_id' => $article->materiau_id,
                    'cmup'        => (float) $niveau->cmup,
                    'quantite'    => (float) $niveau->quantite,
                ]);
            }
        }
    }
}