<?php
namespace App\Listeners\Execution;

use App\Domain\Execution\Events\ChantierCree;
use App\Domain\Socle\Services\JournalService;

class JournaliserChantierCree
{
    public function __construct(private JournalService $journal) {}

    public function handle(ChantierCree $event): void
    {
        $this->journal->log('projet.cree', $event->projet, $event->creeParId, null, $event->toArray());
    }
}