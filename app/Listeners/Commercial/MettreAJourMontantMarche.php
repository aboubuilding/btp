<?php
namespace App\Listeners\Commercial;

use App\Domain\Commercial\Events\AvenantSigne;
use App\Domain\Commercial\Models\Marche;
use App\Domain\Socle\Services\JournalService;

class MettreAJourMontantMarche
{
    public function __construct(private JournalService $journal) {}

    public function handle(AvenantSigne $event): void
    {
        $avenant = $event->avenant;
        $marche = Marche::find($avenant->marche_id);
        if (!$marche) return;

        $nouveauMontant = $marche->montant_initial + $marche->avenantsSignes()->sum('montant');

        $this->journal->log('marche.montant_actualise', $marche, null, null, [
            'avenant_id'       => $avenant->id,
            'nouveau_montant'  => $nouveauMontant,
        ]);
    }
}