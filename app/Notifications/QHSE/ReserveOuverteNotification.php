<?php
namespace App\Notifications\QHSE;

use App\Domain\QHSE\Models\Reserve;
use App\Notifications\BaseNotification;

class ReserveOuverteNotification extends BaseNotification
{
    protected string $couleur  = 'danger';
    protected string $icone    = 'fa-exclamation-circle';
    protected string $priorite = 'normal';

    public function __construct(public Reserve $reserve)
    {
        $this->url = route('qhse.pv-receptions.show', $reserve->pv_reception_id);
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    protected function getTitre(): string
    {
        return '📌 Nouvelle réserve';
    }

    protected function getMessage(): string
    {
        return sprintf(
            'Réserve ouverte sur le PV : %s (localisation : %s). Responsable : %s.',
            $this->tronquer($this->reserve->description, 120),
            $this->reserve->localisation ?? '—',
            $this->reserve->responsable_id ? "ID #{$this->reserve->responsable_id}" : '—'
        );
    }
}