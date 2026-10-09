<?php
namespace App\Domain\QHSE\Events;

use App\Domain\QHSE\Models\PvReception;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReceptionDefinitiveSignee
{
    use Dispatchable, SerializesModels;

    public function __construct(public PvReception $pv) {}

    public function toArray(): array
    {
        return [
            'pv_id'         => $this->pv->id,
            'projet_id'     => $this->pv->projet_id,
            'code_chantier' => $this->pv->projet?->code,
            'date_reception'=> $this->pv->date_reception?->toDateString(),
            'avec_reserves' => $this->pv->avec_reserves,
            'signe_le'      => now()->toIso8601String(),
        ];
    }
}