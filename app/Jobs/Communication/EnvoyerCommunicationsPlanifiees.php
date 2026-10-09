<?php
namespace App\Jobs\Communication;

use App\Domain\Socle\Models\Communication;
use App\Domain\Socle\Services\CommunicationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Log;

class EnvoyerCommunicationsPlanifiees implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300;
    public string $queue = 'communications';

    public function handle(CommunicationService $service): void
    {
        $communications = Communication::where('statut', 'brouillon')
            ->whereNotNull('planifie_le')
            ->where('planifie_le', '<=', now())
            ->where('etat', 1)
            ->get();

        $envoyes = 0;
        $echoues = 0;

        foreach ($communications as $communication) {
            try {
                $service->envoyerEmail($communication);
                $envoyes++;
            } catch (\Throwable $e) {
                $echoues++;
                Log::error('[Job] Échec envoi communication', [
                    'comm_id' => $communication->id,
                    'error'   => $e->getMessage(),
                ]);
            }
        }

        Log::info('[Job] Envoi communications planifiées', [
            'envoyes' => $envoyes,
            'echoues' => $echoues,
        ]);
    }
}