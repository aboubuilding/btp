<?php
namespace App\Listeners\Commercial;

use App\Domain\Commercial\Events\ClientCree;
use App\Domain\Socle\Services\JournalService;

class JournaliserClientCree
{
    public function __construct(private JournalService $journal) {}

    public function handle(ClientCree $event): void
    {
        $this->journal->log('client.cree', $event->client, $event->creeParId, null, $event->toArray());
    }
}