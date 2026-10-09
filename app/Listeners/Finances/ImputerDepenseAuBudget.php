<?php
namespace App\Listeners\Finances;

use App\Domain\Finances\Events\DepenseApprouvee;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Support\Facades\DB;

class ImputerDepenseAuBudget
{
    public function __construct(private JournalService $journal) {}

    public function handle(DepenseApprouvee $event): void
    {
        $depense = $event->depense;
        $projet = $depense->projet;
        if (!$projet) return;

        DB::transaction(function () use ($depense, $projet) {
            $projet->increment('budget_reel', (float) $depense->montant);

            if ($depense->ligne_budget_id) {
                $ligne = $projet->ligneBudgets()->find($depense->ligne_budget_id);
                $ligne?->increment('montant_reel', (float) $depense->montant);
            }

            $this->journal->log('projet.depense_imputee', $projet, null, null, [
                'depense_id' => $depense->id,
                'montant'    => (float) $depense->montant,
            ]);
        });
    }
}