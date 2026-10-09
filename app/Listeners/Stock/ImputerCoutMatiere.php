<?php
namespace App\Listeners\Stock;

use App\Domain\Approvisionnement\Events\SortieStockEnregistree;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImputerCoutMatiere implements ShouldQueue
{
    public string $queue = 'stock';

    public function __construct(private JournalService $journal) {}

    public function handle(SortieStockEnregistree $event): void
    {
        $mouvement = $event->mouvement;
        if (!$mouvement->projet_id) return;

        $cout = (float) $mouvement->quantite * (float) $mouvement->prix_unitaire;
        $projet = $mouvement->projet;
        if (!$projet) return;

        $projet->increment('budget_reel', $cout);

        $ligne = $projet->ligneBudgets()->where('libelle', 'Matériaux')->first();
        $ligne?->increment('montant_reel', $cout);

        $this->journal->log('projet.cout_matiere_impute', $projet, null, null, [
            'mouvement_id' => $mouvement->id,
            'cout'         => $cout,
        ]);
    }
}