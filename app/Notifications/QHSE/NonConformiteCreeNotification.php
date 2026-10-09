<?php
namespace App\Notifications\QHSE;

use App\Domain\QHSE\Models\NonConformite;
use App\Notifications\BaseNotification;

class NonConformiteCreeNotification extends BaseNotification
{
    protected string $couleur  = 'warning';
    protected string $icone    = 'fa-clipboard-check';
    protected string $priorite = 'normal';

    public function __construct(public NonConformite $nonConformite)
    {
        $this->url = route('qhse.non-conformites.index');
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    protected function getTitre(): string
    {
        return '⚠️ Nouvelle non-conformité';
    }

    protected function getMessage(): string
    {
        return sprintf(
            'Une non-conformité a été constatée sur le chantier « %s » le %s — Ouvrage : %s.',
            $this->nonConformite->projet?->code ?? '—',
            $this->formatDate($this->nonConformite->date_constat),
            $this->nonConformite->ouvrage ?? '—'
        );
    }
}