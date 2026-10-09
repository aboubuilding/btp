<?php
namespace App\Jobs\Comptabilite;

use App\Domain\Finances\Models\Facture;
use App\Domain\Approvisionnement\Models\BonCommande;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Log;

class RapprocherFacturesFournisseurs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public string $queue = 'comptabilite';

    public function handle(JournalService $journal): void
    {
        $factures = Facture::where('type', 'fournisseur')
            ->whereNull('bon_commande_id')
            ->where('etat', 1)
            ->get();

        $rapprochees = 0;

        foreach ($factures as $facture) {
            // Cherche un BC correspondant au montant et au fournisseur
            $bc = BonCommande::where('fournisseur_id', $facture->id_facturable)
                ->whereBetween('montant_total', [
                    (float) $facture->montant_ht * 0.95,
                    (float) $facture->montant_ht * 1.05,
                ])
                ->whereDate('date_commande', '>=', $facture->date_facture->subDays(90))
                ->where('statut', 'livre')
                ->first();

            if ($bc) {
                $facture->update(['bon_commande_id' => $bc->id]);
                $rapprochees++;
            }
        }

        $journal->log('job.factures_fournisseurs_rapprochees', null, null, null, ['count' => $rapprochees]);
        Log::info('[Job] Rapprochement factures fournisseurs', ['rapprochees' => $rapprochees]);
    }
}