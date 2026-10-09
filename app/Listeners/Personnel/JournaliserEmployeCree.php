<?php
namespace App\Listeners\Personnel;

use App\Domain\Personnel\Events\EmployeCree;
use App\Domain\Socle\Services\JournalService;

class JournaliserEmployeCree
{
    public function __construct(private JournalService $journal) {}

    public function handle(EmployeCree $event): void
    {
        $this->journal->log('employe.cree', $event->employe, null, null, $event->toArray());
    }
}