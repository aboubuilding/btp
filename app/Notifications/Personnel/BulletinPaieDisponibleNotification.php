<?php
namespace App\Notifications\Personnel;

use App\Domain\Personnel\Models\BulletinPaie;
use App\Notifications\BaseNotification;

class BulletinPaieDisponibleNotification extends BaseNotification
{
    protected string $couleur  = 'success';
    protected string $icone    = 'fa-file-invoice-dollar';
    protected string $priorite = 'high';

    public function __construct(public BulletinPaie $bulletin)
    {
        $this->url = route('rh.bulletins.show', $bulletin);
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    protected function getTitre(): string
    {
        return '💵 Bulletin de paie disponible';
    }

    protected function getMessage(): string
    {
        return sprintf(
            'Votre bulletin pour la période « %s » est disponible. Net à payer : %s.',
            $this->bulletin->periode?->libelle ?? '—',
            $this->formatMontant((float) $this->bulletin->net_a_payer)
        );
    }
}