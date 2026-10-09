<?php
namespace App\Notifications\QHSE;

use App\Domain\QHSE\Models\PvReception;
use App\Notifications\BaseNotification;

class ReceptionDefinitiveSigneeNotification extends BaseNotification
{
    protected string $couleur  = 'success';
    protected string $icone    = 'fa-file-signature';
    protected string $priorite = 'high';

    public function __construct(public PvReception $pv)
    {
        $this->url = route('qhse.pv-receptions.show', $pv);
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    protected function getTitre(): string
    {
        return '🎉 Réception définitive signée';
    }

    protected function getMessage(): string
    {
        return sprintf(
            'La réception définitive du chantier « %s » a été signée le %s. '
            . 'Les retenues de garantie peuvent être libérées.',
            $this->pv->projet?->code ?? '—',
            $this->formatDate($this->pv->date_reception)
        );
    }

    public function toMail(object $notifiable)
    {
        return (new \App\Mail\NotificationMail(
            $this->getTitre(),
            $this->getMessage(),
            $this->url,
            $this->couleur,
            'Voir le PV',
            [
                'Chantier'   => $this->pv->projet?->code . ' — ' . $this->pv->projet?->nom,
                'Type'       => $this->pv->type_label,
                'Date'       => $this->formatDate($this->pv->date_reception),
                'Réserves'   => $this->pv->avec_reserves ? 'Avec réserves' : 'Sans réserve',
                'Statut'     => $this->pv->statut_label,
            ]
        ))->to($notifiable->routeNotificationFor('mail') ?? $notifiable->email);
    }
}