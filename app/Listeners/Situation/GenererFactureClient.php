<?php
namespace App\Listeners\Situation;

use App\Domain\Execution\Events\SituationApprouvee;
use App\Domain\Execution\Services\SituationService;
use App\Domain\Finances\Services\FacturationService;
use App\Domain\Socle\Services\ParametreService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenererFactureClient implements ShouldQueue
{
    public int $tries = 3;
    public string $queue = 'comptabilite';

    public function __construct(
        private FacturationService $facturation,
        private SituationService $situations,
        private ParametreService $parametres,
    ) {}

    public function handle(SituationApprouvee $event): void
    {
        $situation = $event->situation;

        // Évite double facturation
        if ($situation->facture()->exists()) {
            Log::warning('[Listener] Situation déjà facturée', ['situation_id' => $situation->id]);
            return;
        }

        DB::transaction(function () use ($situation) {
            $projet = $situation->projet;

            $facture = $this->facturation->creer([
                'type'                 => 'client',
                'type_facturable'      => get_class($projet->client),
                'id_facturable'        => $projet->client_id,
                'projet_id'            => $projet->id,
                'situation_id'         => $situation->id,
                'date_facture'         => now()->toDateString(),
                'date_echeance'        => now()->addDays(30)->toDateString(),
                'montant_ht'           => $situation->montant_periode_ht,
                'tva'                  => $situation->tva,
                'montant_ttc'          => (float) $situation->montant_periode_ht + (float) $situation->tva,
                'retenue_garantie'     => $situation->retenue_garantie,
                'remboursement_avance' => $situation->remboursement_avance,
                'net_a_payer'          => $situation->net_a_payer,
            ]);

            $this->situations->marquerFacturee($situation);

            Log::info('[Listener] Facture client générée', [
                'situation_id' => $situation->id,
                'facture_id'   => $facture->id,
            ]);
        });
    }
}