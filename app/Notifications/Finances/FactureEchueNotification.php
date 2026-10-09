<?php
namespace App\Notifications\Finances;

use App\Domain\Finances\Models\Facture;
use App\Notifications\BaseNotification;

class FactureEchueNotification extends BaseNotification
{
    protected string $couleur  = 'danger';
    protected string $icone    = 'fa-clock';
    protected string $priorite = 'high';

    public function __construct(public Facture $facture)
    {
        $this->url = route('finances.factures.show', $facture);
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    protected function getTitre(): string
    {
        return '⏰ Facture échue : ' . $this->facture->numero_facture;
    }

    protected function getMessage(): string
    {
        return sprintf(
            'Facture en retard de %d jour(s). Reste à payer : %s.',
            $this->facture->jours_retard,
            $this->formatMontant((float) $this->facture->reste_a_payer)
        );
    }

    public function toMail(object $notifiable)
    {
        return (new \App\Mail\NotificationMail(
            $this->getTitre(),
            $this->getMessage(),
            $this->url,
            $this->couleur,
            'Voir la facture',
            [
                'N° facture'    => $this->facture->numero_facture,
                'Émise le'      => $this->formatDate($this->facture->date_facture),
                'Échéance'      => $this->formatDate($this->facture->date_echeance),
                'Jours retard'  => $this->facture->jours_retard . ' jour(s)',
                'Montant TTC'   => $this->formatMontant((float) $this->facture->montant_ttc),
                'Déjà payé'     => $this->formatMontant((float) $this->facture->montant_paye),
                'Reste à payer' => $this->formatMontant((float) $this->facture->reste_a_payer),
            ]
        ))->to($notifiable->routeNotificationFor('mail') ?? $notifiable->email);
    }
}