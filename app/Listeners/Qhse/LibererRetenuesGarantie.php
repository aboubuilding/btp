<?php
namespace App\Listeners\Qhse;

use App\Domain\QHSE\Events\ReceptionDefinitiveSignee;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Contracts\Queue\ShouldQueue;

class LibererRetenuesGarantie implements ShouldQueue
{
    public string $queue = 'comptabilite';

    public function __construct(private JournalService $journal) {}

    public function handle(ReceptionDefinitiveSignee $event): void
    {
        $pv = $event->pv;
        $projet = $pv->projet;
        if (!$projet) return;

        $marche = $projet->marche;
        if ($marche) {
            $marche->update(['statut' => 'receptionne']);
        }

        $projet->update([
            'statut'                    => 'termine',
            'date_reception_definitive' => $pv->date_reception,
        ]);

        $this->journal->log('projet.retenues_liberables', $projet, null, null, [
            'pv_id'      => $pv->id,
            'marche_id'  => $marche?->id,
        ]);
    }
}