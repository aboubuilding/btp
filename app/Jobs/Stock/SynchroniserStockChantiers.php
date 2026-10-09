<?php
namespace App\Jobs\Stock;

use App\Domain\Execution\Models\Projet;
use App\Domain\Approvisionnement\Models\NiveauStock;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Log;

class SynchroniserStockChantiers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;
    public int $timeout = 900;
    public string $queue = 'stock';

    public function handle(JournalService $journal): void
    {
        $projets = Projet::where('statut', 'en_cours')->get();
        $synchronises = 0;

        foreach ($projets as $projet) {
            $niveaux = NiveauStock::whereHas('entrepot', fn($q) =>
                $q->where('projet_id', $projet->id)
            )->get();

            foreach ($niveaux as $niveau) {
                // Recalcule la quantité réelle depuis les mouvements
                $quantiteReelle = $niveau->mouvements()
                    ->whereIn('type', ['entree', 'transfert_entree', 'ajustement'])
                    ->sum('quantite')
                    - $niveau->mouvements()
                        ->whereIn('type', ['sortie', 'transfert_sortie'])
                        ->sum('quantite');

                if (abs((float) $niveau->quantite - (float) $quantiteReelle) > 0.01) {
                    $niveau->update(['quantite' => $quantiteReelle]);
                    $synchronises++;
                }
            }
        }

        $journal->log('job.stock_synchronise', null, null, null, ['count' => $synchronises]);
        Log::info('[Job] Synchronisation stock', ['synchronises' => $synchronises]);
    }
}