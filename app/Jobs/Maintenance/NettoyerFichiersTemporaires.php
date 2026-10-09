<?php
namespace App\Jobs\Maintenance;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\{Log, Storage};

class NettoyerFichiersTemporaires implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;
    public string $queue = 'maintenance';

    public function __construct(public int $joursConservation = 7) {}

    public function handle(): void
    {
        $dossiers = ['tmp', 'exports/tmp', 'rapports/tmp', 'paie/tmp'];
        $supprimes = 0;

        foreach ($dossiers as $dossier) {
            if (!Storage::disk('local')->exists($dossier)) continue;

            $fichiers = Storage::disk('local')->files($dossier);
            foreach ($fichiers as $fichier) {
                $derniereModif = Storage::disk('local')->lastModified($fichier);
                if ($derniereModif < now()->subDays($this->joursConservation)->timestamp) {
                    Storage::disk('local')->delete($fichier);
                    $supprimes++;
                }
            }
        }

        Log::info('[Job] Fichiers temporaires nettoyés', ['supprimes' => $supprimes]);
    }
}