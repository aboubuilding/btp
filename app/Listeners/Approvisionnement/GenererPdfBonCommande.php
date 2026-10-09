<?php
namespace App\Listeners\Approvisionnement;

use App\Domain\Approvisionnement\Events\BonCommandeValide;
use App\Domain\Socle\Models\Document;
use App\Domain\Socle\Services\PdfService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class GenererPdfBonCommande implements ShouldQueue
{
    public string $queue = 'documents';

    public function __construct(private PdfService $pdf) {}

    public function handle(BonCommandeValide $event): void
    {
        $bc = $event->bonCommande;
        $bc->load(['fournisseur', 'articles.materiau', 'livraisons']);

        try {
            $filename = 'bons-commande/' . $bc->numero . '.pdf';
            $this->pdf->save('pdf.bon-commande', ['bc' => $bc], $filename);

            Document::updateOrCreate(
                [
                    'documentable_type' => get_class($bc),
                    'documentable_id'   => $bc->id,
                    'nom'               => $bc->numero . '.pdf',
                ],
                [
                    'chemin' => $filename,
                    'mime'   => 'application/pdf',
                    'taille' => \Storage::disk('local')->size($filename),
                ]
            );

            Log::info('[Listener] PDF BC généré', ['bc_id' => $bc->id]);
        } catch (\Throwable $e) {
            Log::error('[Listener] Échec PDF BC', [
                'bc_id' => $bc->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}