<?php
namespace App\Listeners\Situation;

use App\Domain\Execution\Events\SituationApprouvee;
use App\Domain\Finances\Models\{EcritureComptable, PlanComptable, ExerciceFiscal};
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class GenererEcritureVente implements ShouldQueue
{
    public string $queue = 'comptabilite';

    public function handle(SituationApprouvee $event): void
    {
        $situation = $event->situation;

        $exercice = ExerciceFiscal::where('statut', 'ouvert')->first();
        if (!$exercice) {
            Log::warning('[Listener] Aucun exercice ouvert — écriture vente non générée');
            return;
        }

        $compteClient = PlanComptable::where('numero', '411')->first();
        $compteVente  = PlanComptable::where('numero', '701')->first();
        $compteTva    = PlanComptable::where('numero', '443')->first();

        if (!$compteClient || !$compteVente) {
            Log::error('[Listener] Comptes SYSCOHADA manquants (411, 701)');
            return;
        }

        $ecriture = EcritureComptable::create([
            'exercice_fiscal_id' => $exercice->id,
            'numero'             => 'EC-VTE-' . $situation->id,
            'date_ecriture'      => now()->toDateString(),
            'libelle'            => "Vente situation #{$situation->numero} - {$situation->projet->code}",
            'statut'             => 'brouillon',
        ]);

        $ecriture->lignes()->createMany([
            ['plan_comptable_id' => $compteClient->id, 'debit' => $situation->net_a_payer, 'credit' => 0],
            ['plan_comptable_id' => $compteVente->id,  'debit' => 0, 'credit' => $situation->montant_periode_ht],
        ]);

        if ($compteTva) {
            $ecriture->lignes()->create([
                'plan_comptable_id' => $compteTva->id,
                'debit'             => 0,
                'credit'            => $situation->tva,
            ]);
        }

        Log::info('[Listener] Écriture vente générée', ['ecriture_id' => $ecriture->id]);
    }
}