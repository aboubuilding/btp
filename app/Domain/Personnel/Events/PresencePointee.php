<?php
namespace App\Domain\Personnel\Events;

use App\Domain\Personnel\Models\Presence;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PresencePointee
{
    use Dispatchable, SerializesModels;

    public function __construct(public Presence $presence) {}

    public function toArray(): array
    {
        return [
            'presence_id'    => $this->presence->id,
            'employe_id'     => $this->presence->employee_id,
            'projet_id'      => $this->presence->projet_id,
            'date'           => $this->presence->date?->toDateString(),
            'statut'         => $this->presence->statut,
            'heures'         => (float) $this->presence->heures_travaillees,
        ];
    }
}