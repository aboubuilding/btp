<?php
namespace App\Jobs\Comptabilite;

use App\Domain\Finances\Models\{Facture, ExerciceFiscal};
use App\Domain\Finances\Services\ComptabiliteService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Log;

class GenererEcrituresEnMasse implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public int $tries = 2;
    public int $timeout = 1800;
    public string $queue = 'comptabilite';

    public function __construct(
        public int $exerciceId,
        public ?string $dateDebut = null,
        public ?string $dateFin = null,
    ) {}

    public function handle(ComptabiliteService $service): void
    {
        $exercice = ExerciceFiscal::find($this->exerciceId);
        if (!$exercice) return;

        $debut = $this->dateDebut ?? $exercice->date_debut->format('Y-m-d');
        $fin   = $this->dateFin   ?? $exercice->date_fin->format('Y-m-d');

        $factures = Facture::whereBetween('date_facture', [$debut, $fin])
            ->whereIn('statut', ['payee', 'partiellement_payee'])
            ->whereDoesntHave('situation')
            ->chunkById(50, function ($factures) use ($exercice, $service) {
                foreach ($factures as $facture) {
                    try {
                        $this->genererEcriture($facture, $exercice->id, $service);
                    } catch (\Throwable $e) {
                        Log::error('[Job] Écriture échouée', [
                            'facture_id' => $facture->id,
                            'error'      => $e->getMessage(),
                        ]);
                    }
                }
            });

        Log::info('[Job] Écritures en masse générées');
    }

    private function genererEcriture(Facture $facture, int $exerciceId, ComptabiliteService $service): void
    {
        $compteClient = \App\Domain\Finances\Models\PlanComptable::where('numero', '411')->first();
        $compteVente  = \App\Domain\Finances\Models\PlanComptable::where('numero', '701')->first();
        $compteTva    = \App\Domain\Finances\Models\PlanComptable::where('numero', '443')->first();

        if (!$compteClient || !$compteVente) return;

        $service->enregistrer(
            [
                'exercice_fiscal_id' => $exerciceId,
                'numero'             => 'EC-AUTO-' . $facture->id,
                'date_ecriture'      => $facture->date_facture,
                'libelle'            => 'Facture ' . $facture->numero_facture,
                'statut'             => 'brouillon',
            ],
            [
                ['plan_comptable_id' => $compteClient->id, 'debit' => $facture->montant_ttc, 'credit' => 0],
                ['plan_comptable_id' => $compteVente->id,  'debit' => 0, 'credit' => $facture->montant_ht],
                ['plan_comptable_id' => $compteTva->id,    'debit' => 0, 'credit' => $facture->tva],
            ]
        );
    }
}