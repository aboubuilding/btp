<?php
namespace App\Notifications\Personnel;

use App\Domain\Personnel\Models\DemandeConge;
use App\Notifications\BaseNotification;

class CongeRefuseNotification extends BaseNotification
{
    protected string $couleur  = 'danger';
    protected string $icone    = 'fa-times-circle';
    protected string $priorite = 'normal';

    public function __construct(public DemandeConge $conge)
    {
        $this->url = route('rh.conges.index');
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    protected function getTitre(): string
    {
        return '❌ Demande de congé refusée';
    }

    protected function getMessage(): string
    {
        return sprintf(
            'Votre demande de congé du %s au %s a été refusée.',
            $this->formatDate($this->conge->date_debut),
            $this->formatDate($this->conge->date_fin)
        );
    }
}