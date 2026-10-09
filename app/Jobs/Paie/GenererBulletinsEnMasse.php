<?php
namespace App\Jobs\Paie;

use App\Domain\Personnel\Models\{PeriodePaie, BulletinPaie};
use App\Domain\Personnel\Services\PaieService;
use App\Notifications\BulletinPaieDisponibleNotification;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Log;

class GenererBulletinsEnMasse implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public int $tries = 2;
    public int $timeout = 900;
    public string $queue = 'paie';

    public function __construct(public int $periodeId) {}

    public function handle(PaieService $service): void
    {
        $periode = PeriodePaie::find($this->periodeId);
        if (!$periode) {
            Log::warning('[Job] Période introuvable', ['id' => $this->periodeId]);
            return;
        }

        $count = $service->genererBulletins($periode);

        Log::info('[Job] Bulletins générés', [
            'periode_id' => $this->periodeId,
            'count'      => $count,
        ]);

        // Notifie chaque employé
        BulletinPaie::where('periode_paie_id', $periode->id)
            ->with('employe.user')
            ->chunkById(50, function ($bulletins) {
                foreach ($bulletins as $bulletin) {
                    $bulletin->employe?->user?->notify(
                        new BulletinPaieDisponibleNotification($bulletin)
                    );
                }
            });
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('[Job] Échec génération bulletins', [
            'periode_id' => $this->periodeId,
            'error'      => $exception->getMessage(),
        ]);
    }
}