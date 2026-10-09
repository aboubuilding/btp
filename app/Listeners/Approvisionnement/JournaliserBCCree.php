<?php
namespace App\Listeners\Approvisionnement;

use App\Domain\Approvisionnement\Events\BonCommandeCree;
use App\Domain\Socle\Services\JournalService;

class JournaliserBCCree
{
    public function __construct(private JournalService $journal) {}

    public function handle(BonCommandeCree $event): void
    {
        $this->journal->log('bon_commande.cree', $event->bonCommande, null, null, $event->toArray());
    }
}