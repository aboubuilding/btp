<?php
namespace App\Notifications\Execution;

use App\Domain\Execution\Models\Projet;
use App\Notifications\BaseNotification;

class ChantierDemarreNotification extends BaseNotification
{
    protected string $couleur  = 'success';
    protected string $icone    = 'fa-play';
    protected string $priorite = 'normal';

    public function __construct(public Projet $projet)
    {
        $this->url = route('projets.show', $projet);
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    protected function getTitre(): string
    {
        return '🚀 Chantier démarré : ' . $this->projet->code;
    }

    protected function getMessage(): string
    {
        return sprintf(
            'Le chantier « %s » (%s) a démarré le %s.',
            $this->projet->nom,
            $this->projet->client?->nom ?? '—',
            $this->formatDate($this->projet->date_debut_reelle)
        );
    }
}