<?php
namespace App\Notifications\QHSE;

use App\Domain\QHSE\Models\IncidentSecurite;
use App\Notifications\BaseNotification;

class IncidentDeclareNotification extends BaseNotification
{
    protected string $couleur  = 'warning';
    protected string $icone    = 'fa-triangle-exclamation';
    protected string $priorite = 'normal';

    public function __construct(public IncidentSecurite $incident)
    {
        $this->url = route('qhse.incidents.show', $incident);
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    protected function getTitre(): string
    {
        return '⚠️ Nouvel incident déclaré';
    }

    protected function getMessage(): string
    {
        return sprintf(
            'Un incident %s (%s) a été déclaré sur le chantier « %s » le %s.',
            str_replace('_', ' ', $this->incident->type),
            $this->incident->gravite,
            $this->incident->projet?->code ?? '—',
            $this->formatDateHeure($this->incident->date_incident)
        );
    }
}