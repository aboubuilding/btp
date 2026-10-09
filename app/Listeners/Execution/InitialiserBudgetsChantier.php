<?php
namespace App\Listeners\Execution;

use App\Domain\Execution\Events\ChantierCree;
use App\Domain\Socle\Services\JournalService;

class InitialiserBudgetsChantier
{
    public function __construct(private JournalService $journal) {}

    public function handle(ChantierCree $event): void
    {
        $projet = $event->projet;

        // Crée les lignes budgétaires par défaut si absentes
        if ($projet->ligneBudgets()->exists()) return;

        $repartition = [
            'Matériaux'       => 0.40,
            "Main d'œuvre"    => 0.25,
            'Matériel'        => 0.15,
            'Sous-traitance'  => 0.15,
            'Frais généraux'  => 0.05,
        ];

        foreach ($repartition as $libelle => $ratio) {
            $projet->ligneBudgets()->create([
                'libelle'       => $libelle,
                'montant_prevu' => round($projet->budget_prevu * $ratio, 2),
                'montant_reel'  => 0,
            ]);
        }

        $this->journal->log('projet.budgets_initialises', $projet, null, null, [
            'montant_total' => $projet->budget_prevu,
        ]);
    }
}