<?php
namespace App\Listeners\SousTraitance;

use App\Domain\SousTraitance\Events\ContratSousTraitantCree;
use App\Domain\Socle\Services\JournalService;

class JournaliserContratCree
{
    public function __construct(private JournalService $journal) {}

    public function handle(ContratSousTraitantCree $event): void
    {
        $this->journal->log('contrat_st.cree', $event->contrat, null, null, $event->toArray());
    }
}