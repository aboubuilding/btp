<?php
namespace App\Jobs\Paie;

use App\Domain\Personnel\Services\PaieService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Log;

class CloturerPeriodePaie implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public string $queue = 'paie';

    public function __construct(
        public int $periodeId,
        public int $userId,
    ) {}

    public function handle(PaieService $service): void
    {
        try {
            $periode = \App\Domain\Personnel\Models\PeriodePaie::findOrFail($this->periodeId);
            $service->cloturer($periode, $this->userId);

            Log::info('[Job] Période clôturée', ['periode_id' => $this->periodeId]);
        } catch (\Throwable $e) {
            Log::error('[Job] Échec clôture période', [
                'periode_id' => $this->periodeId,
                'error'      => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}