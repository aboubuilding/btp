<?php
namespace App\Listeners\Commercial;

use App\Domain\Commercial\Events\DevisCree;
use App\Domain\Socle\Services\JournalService;

class JournaliserDevisCree
{
    public function __construct(private JournalService $journal) {}

    public function handle(DevisCree $event): void
    {
        $this->journal->log('devis.cree', $event->devis, $event->creeParId, null, $event->toArray());
    }
}