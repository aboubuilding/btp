<?php
namespace App\Domain\QHSE\Events;

use App\Domain\QHSE\Models\IncidentSecurite;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IncidentGraveDeclare
{
    use Dispatchable, SerializesModels;

    public function __construct(public IncidentSecurite $incident) {}

    public function toArray(): array
    {
        return [
            'incident_id'    => $this->incident->id,
            'projet_id'      => $this->incident->projet_id,
            'code_chantier'  => $this->incident->projet?->code,
            'nom_chantier'   => $this->incident->projet?->nom,
            'type'           => $this->incident->type,
            'gravite'        => $this->incident->gravite,
            'date_incident'  => $this->incident->date_incident?->toIso8601String(),
            'description'    => $this->incident->description,
            'victimes'       => $this->incident->nombre_victimes,
            'jours_arret'    => $this->incident->jours_arret,
            'declare_par'    => $this->incident->declare_par,
            'urgence'        => true,
        ];
    }
}