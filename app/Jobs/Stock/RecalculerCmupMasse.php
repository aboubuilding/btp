<?php
namespace App\Jobs\Stock;

use App\Domain\Approvisionnement\Models\{NiveauStock, MouvementStock};
use App\Domain\Socle\Services\JournalService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\{DB, Log};

class RecalculerCmupMasse implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;
    public int $timeout = 1800;
    public string $queue = 'stock';

    public function handle(JournalService $journal): void
    {
        $recalculs = 0;

        NiveauStock::with(['materiau', 'entrepot'])
            ->chunkById(50, function ($niveaux) use (&$recalculs) {
                foreach ($niveaux as $niveau) {
                    $this->recalculerPourNiveau($niveau);
                    $recalculs++;
                }
            });

        $journal->log('job.cmup_recalcule', null, null, null, ['count' => $recalculs]);
        Log::info('[Job] CMUP recalculé en masse', ['count' => $recalculs]);
    }

    private function recalculerPourNiveau(NiveauStock $niveau): void
    {
        $mouvements = MouvementStock::where('entrepot_id', $niveau->entrepot_id)
            ->where('materiau_id', $niveau->materiau_id)
            ->whereIn('type', ['entree', 'transfert_entree'])
            ->orderBy('date_mouvement')
            ->get();

        $quantiteCumulee = 0;
        $valeurCumulee = 0;

        foreach ($mouvements as $mouvement) {
            $quantiteCumulee += (float) $mouvement->quantite;
            $valeurCumulee += (float) $mouvement->quantite * (float) $mouvement->prix_unitaire;
        }

        $nouveauCmup = $quantiteCumulee > 0
            ? round($valeurCumulee / $quantiteCumulee, 2)
            : 0;

        if ($nouveauCmup !== (float) $niveau->cmup) {
            $niveau->update(['cmup' => $nouveauCmup]);
        }
    }
}