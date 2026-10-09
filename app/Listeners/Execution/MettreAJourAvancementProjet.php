<?php
namespace App\Listeners\Execution;

use App\Domain\Execution\Events\TacheTerminee;
use App\Domain\Execution\Repositories\ProjetRepositoryInterface;
use App\Domain\Socle\Services\JournalService;

class MettreAJourAvancementProjet
{
    public function __construct(
        private ProjetRepositoryInterface $projets,
        private JournalService $journal,
    ) {}

    public function handle(TacheTerminee $event): void
    {
        $tache = $event->tache;
        $projet = $tache->projet;
        if (!$projet) return;

        $ancienAvancement = $projet->pourcentage_avancement;
        $nouveauAvancement = $this->projets->recalculerAvancement($projet);

        if (round($ancienAvancement, 2) !== round($nouveauAvancement, 2)) {
            $this->journal->log('projet.avancement_recalcule', $projet, null, null, [
                'ancien'  => $ancienAvancement,
                'nouveau' => $nouveauAvancement,
                'tache'   => $tache->id,
            ]);
        }
    }
}