<?php
namespace App\Notifications\Finances;

use App\Domain\Finances\Models\Paiement;
use App\Notifications\BaseNotification;

class PaiementEnregistreNotification extends BaseNotification
{
    protected string $couleur  = 'success';
    protected string $icone    = 'fa-hand-holding-dollar';
    protected string $priorite = 'normal';

    public function __construct(public Paiement $paiement)
    {
        $this->url = route('finances.paiements.index');
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    protected function getTitre(): string
    {
        return $this->paiement->sens === 'encaissement'
            ? '💰 Encaissement reçu'
            : '💸 Décaissement effectué';
    }

    protected function getMessage(): string
    {
        return sprintf(
            '%s de %s enregistré le %s (mode : %s).',
            ucfirst($this->paiement->sens),
            $this->formatMontant((float) $this->paiement->montant),
            $this->formatDate($this->paiement->date_paiement),
            $this->paiement->mode_label
        );
    }
}