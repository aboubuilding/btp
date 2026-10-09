<?php
namespace App\Jobs\Alertes;

use App\Domain\Approvisionnement\Models\NiveauStock;
use App\Domain\Approvisionnement\Events\StockSousSeuil;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\{Log};

class VerifierStockSousSeuil implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 180;
    public string $queue = 'alertes';

    public function handle(JournalService $journal): void
    {
        $count = 0;

        NiveauStock::with(['materiau', 'entrepot'])
            ->whereHas('materiau', fn($q) => $q->where('etat', 1))
            ->whereRaw('quantite <= (SELECT seuil_alerte_stock_min FROM materiaux WHERE materiaux.id = niveau_stocks.materiau_id)')
            ->chunkById(100, function ($niveaux) use (&$count) {
                foreach ($niveaux as $niveau) {
                    event(new StockSousSeuil($niveau->materiau, $niveau));
                    $count++;
                }
            });

        $journal->log('job.stock_sous_seuil_verifie', null, null, null, ['count' => $count]);
        Log::info('[Job] Vérification stock terminée', ['alertes' => $count]);
    }
}