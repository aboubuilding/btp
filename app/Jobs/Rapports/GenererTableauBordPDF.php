<?php
namespace App\Jobs\Rapports;

use App\Domain\Execution\Repositories\ProjetRepositoryInterface;
use App\Domain\Finances\Repositories\FactureRepositoryInterface;
use App\Domain\Approvisionnement\Repositories\StockRepositoryInterface;
use App\Domain\Socle\Services\{CommunicationService, PdfService};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Log;

class GenererTableauBordPDF implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public string $queue = 'rapports';

    public function __construct(public array $destinataires = []) {}

    public function handle(
        PdfService $pdf,
        CommunicationService $communication,
        ProjetRepositoryInterface $projets,
        FactureRepositoryInterface $factures,
        StockRepositoryInterface $stock,
    ): void {
        $stats = array_merge(
            $projets->statistiques(),
            $factures->statistiques(),
            ['valeur_stock' => $stock->valeurTotale()]
        );

        $filename = 'rapports/tableau-bord-' . now()->format('Ymd-His') . '.pdf';
        $pdf->save('pdf.tableau-bord', ['stats' => $stats], $filename);

        if (!empty($this->destinataires)) {
            $communication->envoyerDepuisTemplate(
                'rapport.tableau_bord',
                ['date' => now()->format('d/m/Y')],
                $this->destinataires
            );
        }

        Log::info('[Job] Tableau de bord PDF généré');
    }
}