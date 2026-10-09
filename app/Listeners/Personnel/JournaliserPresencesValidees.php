<?php
namespace App\Listeners\Personnel;

use App\Domain\Personnel\Events\PresencesValidees;
use App\Domain\Socle\Services\JournalService;

class JournaliserPresencesValidees
{
    public function __construct(private JournalService $journal) {}

    public function handle(PresencesValidees $event): void
    {
        $this->journal->log('presences.validees', null, $event->valideParId, null, $event->toArray());
    }
}