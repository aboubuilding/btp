<?php
namespace App\Domain\QHSE\Events;

use App\Domain\QHSE\Models\IncidentSecurite;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IncidentDeclare
{
    use Dispatchable, SerializesModels;

    public function __construct(public IncidentSecurite $incident) {}

    public function toArray(): array
    {
        return [
            'incident_id'    => $this->incident->id,
            'projet_id'      => $this->incident->projet_id,
            'code_chantier'  => $this->incident->projet?->code,
            'type'           => $this->incident->type,
            'gravite'        => $this->incident->gravite,
            'date'           => $this->incident->date_incident?->toIso8601String(),
            'victimes'       => $this->incident->nombre_victimes,
        ];
    }
}