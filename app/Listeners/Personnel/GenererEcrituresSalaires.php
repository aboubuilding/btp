<?php
namespace App\Listeners\Personnel;

use App\Domain\Personnel\Events\PeriodePaieCloturee;
use App\Domain\Finances\Models\{EcritureComptable, PlanComptable, ExerciceFiscal};
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class GenererEcrituresSalaires implements ShouldQueue
{
    public int $tries = 3;
    public string $queue = 'comptabilite';

    public function handle(PeriodePaieCloturee $event): void
    {
        $periode = $event->periode;

        $exercice = ExerciceFiscal::where('statut', 'ouvert')->first();
        if (!$exercice) {
            Log::warning('[Listener] Aucun exercice ouvert — écritures paie non générées');
            return;
        }

        $totalBrut = (float) $periode->bulletins()->sum('brut');
        $totalCs   = (float) $periode->bulletins()->sum('cotisations_salariales');
        $totalCp   = (float) $periode->bulletins()->sum('cotisations_patronales');
        $totalNet  = (float) $periode->bulletins()->sum('net_a_payer');

        $comptePersonnel = PlanComptable::where('numero', '422')->first();
        $compteCharges   = PlanComptable::where('numero', '661')->first();
        $compteCotis     = PlanComptable::where('numero', '431')->first();

        if (!$comptePersonnel || !$compteCharges) {
            Log::error('[Listener] Comptes paie manquants (422, 661)');
            return;
        }

        $ecriture = EcritureComptable::create([
            'exercice_fiscal_id' => $exercice->id,
            'numero'             => 'EC-PAIE-' . $periode->id,
            'date_ecriture'      => $periode->date_fin->format('Y-m-d'),
            'libelle'            => "Paie période {$periode->libelle}",
            'statut'             => 'brouillon',
        ]);

        $ecriture->lignes()->createMany([
            ['plan_comptable_id' => $compteCharges->id, 'debit' => $totalBrut + $totalCp, 'credit' => 0],
            ['plan_comptable_id' => $comptePersonnel->id, 'debit' => 0, 'credit' => $totalNet],
        ]);

        if ($compteCotis) {
            $ecriture->lignes()->create([
                'plan_comptable_id' => $compteCotis->id,
                'debit'             => 0,
                'credit'            => $totalCs + $totalCp,
            ]);
        }

        Log::info('[Listener] Écriture paie générée', [
            'periode_id' => $periode->id,
            'ecriture_id'=> $ecriture->id,
        ]);
    }
}