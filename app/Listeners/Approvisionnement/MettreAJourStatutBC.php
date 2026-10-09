<?php
namespace App\Listeners\Approvisionnement;

use App\Domain\Approvisionnement\Events\LivraisonReceptionnee;

class MettreAJourStatutBC
{
    public function handle(LivraisonReceptionnee $event): void
    {
        $livraison = $event->livraison;
        $bc = $livraison->bonCommande;
        if (!$bc) return;

        $totalCommandees = (float) $bc->articles->sum('quantite');
        $totalRecues = (float) $bc->articles->sum(fn($a) => $a->quantite_recue);

        if ($totalRecues >= $totalCommandees) {
            $bc->update(['statut' => 'livre']);
        } elseif ($totalRecues > 0) {
            $bc->update(['statut' => 'livre_partiellement']);
        }
    }
}