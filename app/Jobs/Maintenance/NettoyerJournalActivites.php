<?php
namespace App\Jobs\Maintenance;

use App\Domain\Socle\Services\JournalService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\{Log, Storage};

class NettoyerJournalActivites implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;
    public int $timeout = 1800;
    public string $queue = 'maintenance';

    public function __construct(public int $joursConservation = 730) {}

    public function handle(JournalService $journalService): void
    {
        $dateLimite = now()->subDays($this->joursConservation);

        // Archive avant suppression
        $logs = \App\Domain\Socle\Models\JournalActivite::where('date_action', '<', $dateLimite)->get();

        if ($logs->isNotEmpty()) {
            $filename = 'archives/journal-' . $dateLimite->format('Ymd') . '.json';
            Storage::disk('local')->put($filename, $logs->toJson(JSON_PRETTY_PRINT));
        }

        $supprimes = $journalService->purgerAvant($this->joursConservation);

        Log::info('[Job] Journal nettoyé', [
            'jours'     => $this->joursConservation,
            'supprimes' => $supprimes,
        ]);
    }
}