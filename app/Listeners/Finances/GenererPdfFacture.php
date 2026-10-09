<?php
namespace App\Listeners\Finances;

use App\Domain\Finances\Events\FactureEmise;
use App\Domain\Socle\Models\Document;
use App\Domain\Socle\Services\PdfService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class GenererPdfFacture implements ShouldQueue
{
    public string $queue = 'documents';

    public function __construct(private PdfService $pdf) {}

    public function handle(FactureEmise $event): void
    {
        $facture = $event->facture;
        $facture->load(['projet', 'facturable', 'situation.attachement.lignes.ligneDevis', 'paiements']);

        try {
            $filename = 'factures/' . $facture->numero_facture . '.pdf';
            $this->pdf->save('pdf.facture', ['facture' => $facture], $filename);

            Document::updateOrCreate(
                [
                    'documentable_type' => get_class($facture),
                    'documentable_id'   => $facture->id,
                    'nom'               => $facture->numero_facture . '.pdf',
                ],
                [
                    'chemin' => $filename,
                    'mime'   => 'application/pdf',
                    'taille' => \Storage::disk('local')->size($filename),
                ]
            );

            Log::info('[Listener] PDF facture généré', ['facture_id' => $facture->id]);
        } catch (\Throwable $e) {
            Log::error('[Listener] Échec PDF facture', [
                'facture_id' => $facture->id,
                'error'      => $e->getMessage(),
            ]);
        }
    }
}