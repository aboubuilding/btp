<?php
namespace App\Notifications\QHSE;

use App\Domain\QHSE\Models\IncidentSecurite;
use App\Notifications\BaseNotification;

class IncidentGraveNotification extends BaseNotification
{
    protected string $couleur  = 'danger';
    protected string $icone    = 'fa-exclamation-triangle';
    protected string $priorite = 'high';

    public function __construct(public IncidentSecurite $incident)
    {
        $this->url = route('qhse.incidents.show', $incident);
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    protected function getTitre(): string
    {
        return '🚨 INCIDENT ' . strtoupper($this->incident->gravite);
    }

    protected function getMessage(): string
    {
        return sprintf(
            "Incident %s sur le chantier « %s » le %s.\nVictimes : %d — Jours d'arrêt : %d\n%s",
            str_replace('_', ' ', $this->incident->type),
            $this->incident->projet?->nom ?? '—',
            $this->formatDateHeure($this->incident->date_incident),
            $this->incident->nombre_victimes,
            $this->incident->jours_arret,
            $this->tronquer($this->incident->description)
        );
    }

    public function toMail(object $notifiable)
    {
        return (new \App\Mail\NotificationMail(
            $this->getTitre(),
            $this->getMessage(),
            $this->url,
            $this->couleur,
            'Voir l\'incident',
            [
                'Chantier'    => $this->incident->projet?->code,
                'Date'        => $this->formatDateHeure($this->incident->date_incident),
                'Type'        => $this->incident->type_label,
                'Gravité'     => $this->incident->gravite_label,
                'Victimes'    => $this->incident->nombre_victimes,
                'Jours arrêt' => $this->incident->jours_arret,
            ]
        ))->to($notifiable->routeNotificationFor('mail') ?? $notifiable->email);
    }
}