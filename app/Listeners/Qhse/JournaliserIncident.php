<?php
namespace App\Listeners\Qhse;

use App\Domain\QHSE\Events\IncidentDeclare;
use App\Domain\Socle\Services\JournalService;

class JournaliserIncident
{
    public function __construct(private JournalService $journal) {}

    public function handle(IncidentDeclare $event): void
    {
        $this->journal->log('incident.declare', $event->incident, null, null, $event->toArray());
    }
}