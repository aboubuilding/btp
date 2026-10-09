<?php
namespace App\Jobs\Comptabilite;

use App\Domain\Finances\Models\{Facture, EcritureComptable, PlanComptable, ExerciceFiscal};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Log;

class GenererEcritureAchat implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public string $queue = 'comptabilite';

    public function __construct(public int $factureId) {}

    public function handle(): void
    {
        $facture = Facture::where('type', 'fournisseur')->find($this->factureId);
        if (!$facture) return;

        $exercice = ExerciceFiscal::where('statut', 'ouvert')->first();
        if (!$exercice) return;

        $compteFournisseur = PlanComptable::where('numero', '401')->first();
        $compteAchat       = PlanComptable::where('numero', '601')->first();
        $compteTva         = PlanComptable::where('numero', '445')->first();

        if (!$compteFournisseur || !$compteAchat) return;

        $ecriture = EcritureComptable::create([
            'exercice_fiscal_id' => $exercice->id,
            'numero'             => 'EC-ACH-' . $facture->id,
            'date_ecriture'      => $facture->date_facture,
            'libelle'            => 'Facture fournisseur ' . $facture->numero_facture,
            'statut'             => 'brouillon',
        ]);

        $ecriture->lignes()->createMany([
            ['plan_comptable_id' => $compteAchat->id,       'debit' => $facture->montant_ht, 'credit' => 0],
            ['plan_comptable_id' => $compteFournisseur->id, 'debit' => 0, 'credit' => $facture->montant_ttc],
        ]);

        if ($compteTva) {
            $ecriture->lignes()->create([
                'plan_comptable_id' => $compteTva->id,
                'debit'             => $facture->tva,
                'credit'            => 0,
            ]);
        }

        Log::info('[Job] Écriture achat générée', ['facture_id' => $this->factureId]);
    }
}