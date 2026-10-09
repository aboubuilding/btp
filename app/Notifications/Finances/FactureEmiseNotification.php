<?php
namespace App\Notifications\Finances;

use App\Domain\Finances\Models\Facture;
use App\Notifications\BaseNotification;

class FactureEmiseNotification extends BaseNotification
{
    protected string $couleur  = 'info';
    protected string $icone    = 'fa-file-invoice';
    protected string $priorite = 'normal';

    public function __construct(public Facture $facture)
    {
        $this->url = route('finances.factures.show', $facture);
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    protected function getTitre(): string
    {
        return '📄 Facture émise : ' . $this->facture->numero_facture;
    }

    protected function getMessage(): string
    {
        return sprintf(
            'Facture de %s émise le %s. Échéance : %s.',
            $this->formatMontant((float) $this->facture->net_a_payer),
            $this->formatDate($this->facture->date_facture),
            $this->formatDate($this->facture->date_echeance)
        );
    }
}