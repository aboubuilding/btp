<?php
namespace App\Notifications\Execution;

use App\Domain\Execution\Models\JalonProjet;
use App\Notifications\BaseNotification;

class JalonsAtteintNotification extends BaseNotification
{
    protected string $couleur  = 'success';
    protected string $icone    = 'fa-flag-checkered';
    protected string $priorite = 'normal';

    public function __construct(public JalonProjet $jalon)
    {
        $this->url = route('projets.show', $jalon->projet_id);
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    protected function getTitre(): string
    {
        return '✅ Jalon atteint';
    }

    protected function getMessage(): string
    {
        return sprintf(
            'Le jalon « %s » du chantier « %s » a été atteint le %s.',
            $this->jalon->libelle,
            $this->jalon->projet?->code ?? '—',
            $this->formatDate($this->jalon->date_atteinte)
        );
    }
}