<?php
namespace App\Listeners\Finances;

use App\Domain\Finances\Events\FactureEmise;
use App\Domain\Socle\Services\CommunicationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class EnvoyerFactureParEmail implements ShouldQueue
{
    public int $tries = 3;
    public string $queue = 'communications';

    public function __construct(private CommunicationService $communication) {}

    public function handle(FactureEmise $event): void
    {
        $facture = $event->facture;

        if ($facture->type !== 'client') return;

        $client = $facture->facturable;
        if (!$client?->email) return;

        try {
            $this->communication->envoyerDepuisTemplate(
                'facture.envoi',
                [
                    'client_nom'    => $client->nom,
                    'numero'        => $facture->numero_facture,
                    'montant_ttc'   => number_format($facture->montant_ttc, 0, ',', ' ') . ' FCFA',
                    'date_echeance' => $facture->date_echeance?->format('d/m/Y') ?? '—',
                ],
                [['email' => $client->email, 'nom' => $client->nom]],
                $facture
            );

            Log::info('[Listener] Facture envoyée par email', [
                'facture_id' => $facture->id,
                'email'      => $client->email,
            ]);
        } catch (\Throwable $e) {
            Log::error('[Listener] Échec envoi facture email', [
                'facture_id' => $facture->id,
                'error'      => $e->getMessage(),
            ]);
        }
    }
}