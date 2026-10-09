<?php
namespace App\Listeners\Socle;

use App\Domain\Socle\Events\UtilisateurCree;
use App\Domain\Socle\Services\JournalService;

class JournaliserUtilisateurCree
{
    public function __construct(private JournalService $journal) {}

    public function handle(UtilisateurCree $event): void
    {
        $this->journal->log(
            'utilisateur.cree',
            $event->user,
            $event->creeParId ?? auth()->id(),
            null,
            $event->toArray()
        );
    }
}