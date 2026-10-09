<?php
namespace App\Notifications\Personnel;

use App\Domain\Personnel\Models\DemandeConge;
use App\Notifications\BaseNotification;

class CongeDemandeNotification extends BaseNotification
{
    protected string $couleur  = 'info';
    protected string $icone    = 'fa-umbrella-beach';
    protected string $priorite = 'normal';

    public function __construct(public DemandeConge $conge)
    {
        $this->url = route('rh.conges.index');
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    protected function getTitre(): string
    {
        return '📅 Nouvelle demande de congé';
    }

    protected function getMessage(): string
    {
        return sprintf(
            '%s demande un congé du %s au %s (%d jours).',
            $this->conge->employe?->nom_complet ?? '—',
            $this->formatDate($this->conge->date_debut),
            $this->formatDate($this->conge->date_fin),
            $this->conge->nombre_jours
        );
    }
}