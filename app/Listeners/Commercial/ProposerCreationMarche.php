<?php
namespace App\Listeners\Commercial;

use App\Domain\Commercial\Events\DevisAccepte;
use App\Domain\Commercial\Models\Marche;
use App\Domain\Socle\Services\JournalService;

class ProposerCreationMarche
{
    public function __construct(private JournalService $journal) {}

    public function handle(DevisAccepte $event): void
    {
        // Crée automatiquement un marché en brouillon à partir du devis accepté
        if ($event->devis->marche()->exists()) {
            return;
        }

        $marche = Marche::create([
            'reference'         => 'MAR-' . date('Y') . '-' . str_pad(Marche::whereYear('created_at', now()->year)->count() + 1, 4, '0', STR_PAD_LEFT),
            'client_id'         => $event->devis->client_id,
            'devis_id'          => $event->devis->id,
            'objet'             => $event->devis->objet,
            'montant_initial'   => $event->devis->montant_ht,
            'taux_avance'       => 15,
            'taux_retenue_garantie' => 5,
            'statut'            => 'brouillon',
        ]);

        $this->journal->log('marche.cree_auto_depuis_devis', $marche, null, null, [
            'devis_id' => $event->devis->id,
        ]);
    }
}