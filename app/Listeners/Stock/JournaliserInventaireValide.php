<?php
namespace App\Listeners\Stock;

use App\Domain\Approvisionnement\Events\InventaireValide;
use App\Domain\Socle\Services\JournalService;

class JournaliserInventaireValide
{
    public function __construct(private JournalService $journal) {}

    public function handle(InventaireValide $event): void
    {
        $this->journal->log('inventaire.valide', $event->inventaire, $event->valideParId, null, $event->toArray());
    }
}