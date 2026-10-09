<?php
namespace App\Listeners\Qhse;

use App\Domain\QHSE\Events\ReserveLevee;
use App\Domain\Socle\Services\JournalService;

class VerifierToutesReservesLevees
{
    public function __construct(private JournalService $journal) {}

    public function handle(ReserveLevee $event): void
    {
        $reserve = $event->reserve;
        $pv = $reserve->pv;

        if ($pv && $pv->toutes_reserves_levees) {
            $this->journal->log('pv.toutes_reserves_levees', $pv, $event->leveParId, null, [
                'pv_id' => $pv->id,
            ]);
        }
    }
}