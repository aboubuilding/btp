<?php
namespace App\Jobs\Communication;

use App\Domain\Socle\Models\Communication;
use App\Domain\Socle\Services\CommunicationService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Log;

class EnvoyerEmailEnMasse implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public int $tries = 3;
    public int $timeout = 120;
    public string $queue = 'communications';

    public function __construct(
        public int $communicationId,
    ) {}

    public function handle(CommunicationService $service): void
    {
        if ($this->batch()?->cancelled()) return;

        $communication = Communication::find($this->communicationId);

        if (!$communication) {
            Log::warning('[Job] Communication introuvable', ['id' => $this->communicationId]);
            return;
        }

        if ($communication->statut === 'envoye') {
            Log::info('[Job] Communication déjà envoyée', ['id' => $this->communicationId]);
            return;
        }

        $service->envoyerEmail($communication);
    }
}