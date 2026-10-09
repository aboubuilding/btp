<?php
namespace App\Notifications\Execution;

use App\Domain\Execution\Models\Situation;
use App\Notifications\BaseNotification;

class SituationValideeNotification extends BaseNotification
{
    protected string $couleur  = 'info';
    protected string $icone    = 'fa-check-double';
    protected string $priorite = 'normal';

    public function __construct(public Situation $situation)
    {
        $this->url = route('situations.situations.show', $situation);
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    protected function getTitre(): string
    {
        return '✔️ Situation #' . $this->situation->numero . ' validée';
    }

    protected function getMessage(): string
    {
        return sprintf(
            'La situation #%d du chantier « %s » a été validée. '
            . 'Net à payer : %s.',
            $this->situation->numero,
            $this->situation->projet?->code ?? '—',
            $this->formatMontant((float) $this->situation->net_a_payer)
        );
    }
}