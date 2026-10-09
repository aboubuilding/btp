<?php
namespace App\Jobs\Comptabilite;

use App\Domain\Execution\Models\Situation;
use App\Domain\Finances\Models\{EcritureComptable, PlanComptable, ExerciceFiscal};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Log;

class GenererEcritureVente implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public string $queue = 'comptabilite';

    public function __construct(public int $situationId) {}

    public function handle(): void
    {
        $situation = Situation::with('projet')->find($this->situationId);
        if (!$situation) return;

        $exercice = ExerciceFiscal::where('statut', 'ouvert')->first();
        if (!$exercice) {
            Log::warning('[Job] Aucun exercice ouvert');
            return;
        }

        $compteClient = PlanComptable::where('numero', '411')->first();
        $compteVente  = PlanComptable::where('numero', '701')->first();
        $compteTva    = PlanComptable::where('numero', '443')->first();

        if (!$compteClient || !$compteVente) return;

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

        Log::info('[Job] Écriture vente générée', ['situation_id' => $this->situationId]);
    }
}