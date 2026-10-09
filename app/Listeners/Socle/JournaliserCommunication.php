<?php
namespace App\Listeners\Socle;

use App\Domain\Socle\Events\CommunicationEnvoyee;
use App\Domain\Socle\Services\JournalService;

class JournaliserCommunication
{
    public function __construct(private JournalService $journal) {}

    public function handle(CommunicationEnvoyee $event): void
    {
        $this->journal->log('communication.envoyee', $event->communication, null, null, $event->toArray());
    }
}