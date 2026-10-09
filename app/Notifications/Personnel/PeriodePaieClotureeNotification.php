<?php
namespace App\Notifications\Personnel;

use App\Domain\Personnel\Models\PeriodePaie;
use App\Notifications\BaseNotification;

class PeriodePaieClotureeNotification extends BaseNotification
{
    protected string $couleur  = 'info';
    protected string $icone    = 'fa-lock';
    protected string $priorite = 'normal';

    public function __construct(public PeriodePaie $periode)
    {
        $this->url = route('rh.periodes-paie.show', $periode);
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    protected function getTitre(): string
    {
        return '🔒 Période de paie clôturée';
    }

    protected function getMessage(): string
    {
        return sprintf(
            'La période « %s » a été clôturée. Total net à payer : %s pour %d bulletins.',
            $this->periode->libelle,
            $this->formatMontant($this->periode->total_net),
            $this->periode->bulletins()->count()
        );
    }

    public function toMail(object $notifiable)
    {
        return (new \App\Mail\NotificationMail(
            $this->getTitre(),
            $this->getMessage(),
            $this->url,
            $this->couleur,
            'Voir la période',
            [
                'Période'      => $this->periode->libelle,
                'Type'         => ucfirst($this->periode->type),
                'Du'           => $this->formatDate($this->periode->date_debut),
                'Au'           => $this->formatDate($this->periode->date_fin),
                'Total brut'   => $this->formatMontant($this->periode->total_brut),
                'Total net'    => $this->formatMontant($this->periode->total_net),
                'Nb bulletins' => $this->periode->bulletins()->count(),
            ]
        ))->to($notifiable->routeNotificationFor('mail') ?? $notifiable->email);
    }
}