<?php
namespace App\Jobs\Documents;

use App\Domain\Execution\Models\Situation;
use App\Domain\Socle\Models\Document;
use App\Domain\Socle\Services\PdfService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Log;

class GenererSituationPDF implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public string $queue = 'documents';

    public function __construct(public int $situationId) {}

    public function handle(PdfService $pdf): void
    {
        $situation = Situation::with([
            'projet.client', 'projet.marche',
            'attachement.lignes.ligneDevis', 'attachement.etabliPar', 'validePar',
        ])->find($this->situationId);

        if (!$situation) return;

        $filename = sprintf(
            'situations/SIT-%s-%s.pdf',
            $situation->projet->code,
            $situation->numero
        );

        $pdf->save('pdf.situation', ['situation' => $situation], $filename);

        Document::updateOrCreate(
            [
                'documentable_type' => Situation::class,
                'documentable_id'   => $situation->id,
                'nom'               => "Situation_{$situation->numero}.pdf",
            ],
            [
                'chemin' => $filename,
                'mime'   => 'application/pdf',
                'taille' => \Storage::disk('local')->size($filename),
            ]
        );

        Log::info('[Job] PDF situation généré', ['situation_id' => $this->situationId]);
    }
}