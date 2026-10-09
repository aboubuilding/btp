<?php
namespace App\Listeners\Execution;

use App\Domain\Execution\Events\ChantierTermine;
use App\Domain\Socle\Services\{JournalService, PdfService};
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class GenererBilanFinancierChantier implements ShouldQueue
{
    public int $timeout = 300;
    public string $queue = 'rapports';

    public function __construct(
        private PdfService $pdf,
        private JournalService $journal,
    ) {}

    public function handle(ChantierTermine $event): void
    {
        $projet = $event->projet;

        try {
            $filename = 'rapports/bilan-chantier-' . $projet->code . '.pdf';
            $this->pdf->save('pdf.bilan-chantier', ['projet' => $projet], $filename);

            $this->journal->log('projet.bilan_financier_genere', $projet, null, null, [
                'filename'    => $filename,
                'budget_prevu'=> (float) $projet->budget_prevu,
                'budget_reel' => (float) $projet->budget_reel,
                'ecart'       => (float) $projet->ecart_budget,
            ]);

            Log::info('[Listener] Bilan financier généré', [
                'projet_id' => $projet->id,
                'filename'  => $filename,
            ]);
        } catch (\Throwable $e) {
            Log::error('[Listener] Échec génération bilan', [
                'projet_id' => $projet->id,
                'error'     => $e->getMessage(),
            ]);
        }
    }
}