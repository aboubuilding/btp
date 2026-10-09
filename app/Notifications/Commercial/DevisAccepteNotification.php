<?php
namespace App\Notifications\Commercial;

use App\Domain\Commercial\Models\Devis;
use App\Notifications\BaseNotification;

class DevisAccepteNotification extends BaseNotification
{
    protected string $couleur  = 'success';
    protected string $icone    = 'fa-check-circle';
    protected string $priorite = 'high';

    public function __construct(public Devis $devis)
    {
        $this->url = route('commercial.devis.show', $devis);
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    protected function getTitre(): string
    {
        return '✅ Devis accepté : ' . $this->devis->numero;
    }

    protected function getMessage(): string
    {
        return sprintf(
            'Le client « %s » a accepté le devis « %s » d\'un montant de %s.',
            $this->devis->client?->nom ?? '—',
            $this->devis->objet,
            $this->formatMontant((float) $this->devis->montant_ttc)
        );
    }

    public function toMail(object $notifiable)
    {
        return (new \App\Mail\NotificationMail(
            $this->getTitre(),
            $this->getMessage(),
            $this->url,
            $this->couleur,
            'Voir le devis',
            [
                'Client'         => $this->devis->client?->nom,
                'Objet'          => $this->devis->objet,
                'Montant HT'     => $this->formatMontant((float) $this->devis->montant_ht),
                'Montant TTC'    => $this->formatMontant((float) $this->devis->montant_ttc),
                'Marge prévue'   => $this->formatMontant((float) $this->devis->marge_previsionnelle),
            ]
        ))->to($notifiable->routeNotificationFor('mail') ?? $notifiable->email);
    }
}