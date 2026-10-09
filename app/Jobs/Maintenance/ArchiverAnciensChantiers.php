<?php
namespace App\Jobs\Maintenance;

use App\Domain\Execution\Models\Projet;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Log;

class ArchiverAnciensChantiers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;
    public string $queue = 'maintenance';

    public function __construct(public int $anneesConservation = 5) {}

    public function handle(): void
    {
        $dateLimite = now()->subYears($this->anneesConservation);

        $archives = Projet::where('statut', 'termine')
            ->whereDate('date_fin_reelle', '<', $dateLimite)
            ->where('etat', 1)
            ->update(['etat' => 0]);

        Log::info('[Job] Chantiers archivés', [
            'annees'     => $this->anneesConservation,
            'archives'   => $archives,
        ]);
    }
}