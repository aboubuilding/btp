<?php
namespace App\Jobs\Documents;

use App\Domain\QHSE\Models\PvReception;
use App\Domain\Socle\Models\Document;
use App\Domain\Socle\Services\PdfService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Log;

class GenererPVReceptionPDF implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public string $queue = 'documents';

    public function __construct(public int $pvId) {}

    public function handle(PdfService $pdf): void
    {
        $pv = PvReception::with(['projet', 'reserves'])->find($this->pvId);
        if (!$pv) return;

        $filename = 'pv-reception/' . $pv->id . '.pdf';
        $pdf->save('pdf.pv-reception', ['pv' => $pv], $filename);

        Document::updateOrCreate(
            [
                'documentable_type' => PvReception::class,
                'documentable_id'   => $pv->id,
                'nom'               => "PV_Reception_{$pv->id}.pdf",
            ],
            [
                'chemin' => $filename,
                'mime'   => 'application/pdf',
                'taille' => \Storage::disk('local')->size($filename),
            ]
        );

        Log::info('[Job] PDF PV réception généré', ['pv_id' => $this->pvId]);
    }
}