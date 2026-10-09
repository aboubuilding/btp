<?php
namespace App\Listeners\Finances;

use App\Domain\Finances\Events\EcritureValidee;
use App\Domain\Socle\Services\JournalService;

class JournaliserEcritureValidee
{
    public function __construct(private JournalService $journal) {}

    public function handle(EcritureValidee $event): void
    {
        $this->journal->log('ecriture.validee', $event->ecriture, $event->valideParId);
    }
}