<?php
namespace App\Jobs\Communication;

use App\Domain\Finances\Models\Facture;
use App\Domain\Socle\Services\CommunicationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Log;

class RelancerFacturesImpayees implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 600;
    public string $queue = 'communications';

    public function handle(CommunicationService $service): void
    {
        $factures = Facture::with(['facturable', 'projet'])
            ->whereIn('statut', ['emise', 'partiellement_payee'])
            ->whereDate('date_echeance', '<', now())
            ->whereRaw('montant_paye < net_a_payer')
            ->where('etat', 1)
            ->get();

        $envoyes = 0;

        foreach ($factures as $facture) {
            $client = $facture->facturable;
            if (!$client?->email) continue;

            try {
                $joursRetard = (int) $facture->date_echeance->diffInDays(now());

                $service->envoyerDepuisTemplate(
                    'facture.relance',
                    [
                        'client_nom'    => $client->nom,
                        'numero'        => $facture->numero_facture,
                        'montant_du'    => number_format($facture->reste_a_payer, 0, ',', ' ') . ' FCFA',
                        'jours_retard'  => $joursRetard,
                        'date_echeance' => $facture->date_echeance->format('d/m/Y'),
                    ],
                    [['email' => $client->email, 'nom' => $client->nom]],
                    $facture
                );
                $envoyes++;
            } catch (\Throwable $e) {
                Log::error('[Job] Relance facture échouée', [
                    'facture' => $facture->numero_facture,
                    'error'   => $e->getMessage(),
                ]);
            }
        }

        Log::info('[Job] Relances factures', ['envoyes' => $envoyes, 'total' => $factures->count()]);
    }
}