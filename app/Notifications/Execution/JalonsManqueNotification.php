<?php
namespace App\Notifications\Execution;

use App\Domain\Execution\Models\JalonProjet;
use App\Notifications\BaseNotification;

class JalonsManqueNotification extends BaseNotification
{
    protected string $couleur  = 'danger';
    protected string $icone    = 'fa-flag';
    protected string $priorite = 'high';

    public function __construct(public JalonProjet $jalon)
    {
        $this->url = route('projets.show', $jalon->projet_id);
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    protected function getTitre(): string
    {
        return '🚩 Jalon manqué';
    }

    protected function getMessage(): string
    {
        $jours = (int) now()->diffInDays($this->jalon->date_echeance);
        return sprintf(
            'Le jalon « %s » du chantier « %s » devait être atteint le %s (%d jour(s) de retard).',
            $this->jalon->libelle,
            $this->jalon->projet?->code ?? '—',
            $this->formatDate($this->jalon->date_echeance),
            $jours
        );
    }
}