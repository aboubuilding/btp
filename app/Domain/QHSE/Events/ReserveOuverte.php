<?php
namespace App\Domain\QHSE\Events;

use App\Domain\QHSE\Models\Reserve;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReserveOuverte
{
    use Dispatchable, SerializesModels;

    public function __construct(public Reserve $reserve) {}

    public function toArray(): array
    {
        return [
            'reserve_id'   => $this->reserve->id,
            'pv_id'        => $this->reserve->pv_reception_id,
            'localisation' => $this->reserve->localisation,
            'description'  => $this->reserve->description,
            'responsable'  => $this->reserve->responsable_id,
            'date_limite'  => $this->reserve->date_limite?->toDateString(),
        ];
    }
}