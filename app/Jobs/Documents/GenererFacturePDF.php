<?php
namespace App\Jobs\Documents;

use App\Domain\Finances\Models\Facture;
use App\Domain\Socle\Models\Document;
use App\Domain\Socle\Services\PdfService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Log;

class GenererFacturePDF implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public string $queue = 'documents';

    public function __construct(public int $factureId) {}

    public function handle(PdfService $pdf): void
    {
        $facture = Facture::with(['projet', 'facturable', 'situation.attachement.lignes.ligneDevis', 'paiements'])
            ->find($this->factureId);

        if (!$facture) return;

        $filename = 'factures/' . $facture->numero_facture . '.pdf';

        $pdf->save('pdf.facture', ['facture' => $facture], $filename);

        // Enregistre comme document rattaché
        Document::updateOrCreate(
            [
                'documentable_type' => Facture::class,
                'documentable_id'   => $facture->id,
                'nom'               => $facture->numero_facture . '.pdf',
            ],
            [
                'chemin' => $filename,
                'mime'   => 'application/pdf',
                'taille' => \Storage::disk('local')->size($filename),
            ]
        );

        Log::info('[Job] PDF facture généré', ['facture_id' => $this->factureId]);
    }
}