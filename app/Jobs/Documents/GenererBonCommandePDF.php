<?php
namespace App\Jobs\Documents;

use App\Domain\Approvisionnement\Models\BonCommande;
use App\Domain\Socle\Models\Document;
use App\Domain\Socle\Services\PdfService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Log;

class GenererBonCommandePDF implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public string $queue = 'documents';

    public function __construct(public int $bonCommandeId) {}

    public function handle(PdfService $pdf): void
    {
        $bc = BonCommande::with(['fournisseur', 'articles.materiau', 'livraisons'])
            ->find($this->bonCommandeId);

        if (!$bc) return;

        $filename = 'bons-commande/' . $bc->numero . '.pdf';
        $pdf->save('pdf.bon-commande', ['bc' => $bc], $filename);

        Document::updateOrCreate(
            [
                'documentable_type' => BonCommande::class,
                'documentable_id'   => $bc->id,
                'nom'               => $bc->numero . '.pdf',
            ],
            [
                'chemin' => $filename,
                'mime'   => 'application/pdf',
                'taille' => \Storage::disk('local')->size($filename),
            ]
        );

        Log::info('[Job] PDF BC généré', ['bc_id' => $this->bonCommandeId]);
    }
}