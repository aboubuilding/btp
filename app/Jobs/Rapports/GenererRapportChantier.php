<?php
namespace App\Jobs\Rapports;

use App\Domain\Execution\Models\Projet;
use App\Domain\Socle\Services\{CommunicationService, PdfService};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Log;

class GenererRapportChantier implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300;
    public string $queue = 'rapports';

    public function __construct(
        public int $projetId,
        public array $destinataires = [],
    ) {}

    public function handle(PdfService $pdf, CommunicationService $communication): void
    {
        $projet = Projet::with([
            'client', 'marche', 'conducteur', 'chefChantier',
            'phases.taches', 'jalons', 'ligneBudgets',
            'situations', 'depenses', 'equipe.poste',
            'incidents', 'equipements',
        ])->find($this->projetId);

        if (!$projet) {
            Log::warning('[Job] Projet introuvable', ['id' => $this->projetId]);
            return;
        }

        // Génère le PDF
        $filename = 'rapports/chantier-' . $projet->code . '-' . now()->format('Ymd-His') . '.pdf';
        $pdf->save('pdf.rapport-chantier', ['projet' => $projet], $filename);

        // Envoi par email si destinataires
        if (!empty($this->destinataires)) {
            $communication->envoyerDepuisTemplate(
                'rapport.chantier',
                [
                    'chantier' => $projet->code . ' — ' . $projet->nom,
                    'contenu'  => "Veuillez trouver ci-joint le rapport complet du chantier.",
                ],
                $this->destinataires,
                $projet
            );
        }

        Log::info('[Job] Rapport chantier généré', ['projet_id' => $projet->id]);
    }
}