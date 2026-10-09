<?php
namespace App\Jobs\Comptabilite;

use App\Domain\Finances\Models\ExerciceFiscal;
use App\Domain\Finances\Services\ComptabiliteService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Log;

class CloturerExerciceFiscal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;
    public string $queue = 'comptabilite';

    public function __construct(
        public int $exerciceId,
        public int $userId,
    ) {}

    public function handle(): void
    {
        $exercice = ExerciceFiscal::findOrFail($this->exerciceId);

        // Vérifie qu'aucune écriture brouillon n'existe
        $brouillons = $exercice->ecritures()->where('statut', 'brouillon')->count();
        if ($brouillons > 0) {
            Log::warning('[Job] Clôture impossible : écritures brouillon', [
                'exercice_id' => $this->exerciceId,
                'count'       => $brouillons,
            ]);
            throw new \RuntimeException("Impossible de clôturer : {$brouillons} écritures en brouillon.");
        }

        $exercice->update(['statut' => 'cloture']);

        Log::info('[Job] Exercice clôturé', [
            'exercice_id' => $this->exerciceId,
            'user_id'     => $this->userId,
        ]);
    }
}