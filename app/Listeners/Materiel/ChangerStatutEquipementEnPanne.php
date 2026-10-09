<?php
namespace App\Listeners\Materiel;

use App\Domain\ParcMateriel\Events\PanneDeclaree;

class ChangerStatutEquipementEnPanne
{
    public function handle(PanneDeclaree $event): void
    {
        $equipement = $event->panne->equipement;
        if ($equipement && $equipement->statut !== 'en_panne') {
            $equipement->update(['statut' => 'en_panne']);
        }
    }
}