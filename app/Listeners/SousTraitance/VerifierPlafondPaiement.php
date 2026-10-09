<?php
namespace App\Listeners\SousTraitance;

use App\Domain\SousTraitance\Events\PaiementSousTraitantEnregistre;
use App\Domain\SousTraitance\Events\PaiementPlafondAtteint;
use App\Domain\Socle\Services\JournalService;

class VerifierPlafondPaiement
{
    public function __construct(private JournalService $journal) {}

    public function handle(PaiementSousTraitantEnregistre $event): void
    {
        $paiement = $event->paiement;
        $contrat = $paiement->facture?->contrat;

        if (!$contrat) return;

        $taux = $contrat->taux_avancement;

        if ($taux >= 90) {
            event(new PaiementPlafondAtteint($contrat));

            $this->journal->log('contrat_st.plafond_proche', $contrat, null, null, [
                'taux'         => $taux,
                'total_paye'   => $contrat->total_paye,
                'plafond'      => $contrat->plafond_paiement,
            ]);
        }
    }
}