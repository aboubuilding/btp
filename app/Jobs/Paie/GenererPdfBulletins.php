<?php
namespace App\Jobs\Paie;

use App\Domain\Personnel\Models\{PeriodePaie, BulletinPaie};
use App\Domain\Socle\Services\PdfService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Log;

class GenererPdfBulletins implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public int $tries = 3;
    public int $timeout = 1800; // 30 min
    public string $queue = 'paie';

    public function __construct(public int $periodeId) {}

    public function handle(PdfService $pdf): void
    {
        $periode = PeriodePaie::find($this->periodeId);
        if (!$periode) return;

        BulletinPaie::where('periode_paie_id', $periode->id)
            ->with(['employe.poste', 'periode'])
            ->chunkById(20, function ($bulletins) use ($pdf, $periode) {
                foreach ($bulletins as $bulletin) {
                    try {
                        $filename = sprintf(
                            'paie/%s/bulletin-%s-%s.pdf',
                            $periode->libelle,
                            $bulletin->employe->matricule,
                            $periode->id
                        );

                        $pdf->save('pdf.bulletin', ['bulletin' => $bulletin], $filename);
                    } catch (\Throwable $e) {
                        Log::error('[Job] PDF bulletin échoué', [
                            'bulletin_id' => $bulletin->id,
                            'error'       => $e->getMessage(),
                        ]);
                    }
                }
            });

        Log::info('[Job] PDF bulletins générés', ['periode_id' => $this->periodeId]);
    }
}