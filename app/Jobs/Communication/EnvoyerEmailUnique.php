<?php
namespace App\Jobs\Communication;

use App\Domain\Socle\Models\Communication;
use App\Domain\Socle\Services\CommunicationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};

class EnvoyerEmailUnique implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public string $queue = 'communications';

    public function __construct(public int $communicationId) {}

    public function handle(CommunicationService $service): void
    {
        $communication = Communication::find($this->communicationId);
        if ($communication) {
            $service->envoyerEmail($communication);
        }
    }
}