<?php
namespace App\Listeners\Materiel;

use App\Domain\ParcMateriel\Events\EquipementAffecte;
use App\Domain\Socle\Services\JournalService;

class JournaliserAffectation
{
    public function __construct(private JournalService $journal) {}

    public function handle(EquipementAffecte $event): void
    {
        $this->journal->log('equipement.affecte', $event->affectation, null, null, $event->toArray());
    }
}