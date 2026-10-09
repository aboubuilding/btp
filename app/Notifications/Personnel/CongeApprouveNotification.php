<?php
namespace App\Notifications\Personnel;

use App\Domain\Personnel\Models\DemandeConge;
use App\Notifications\BaseNotification;

class CongeApprouveNotification extends BaseNotification
{
    protected string $couleur  = 'success';
    protected string $icone    = 'fa-check-circle';
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
        return '✅ Congé approuvé';
    }

    protected function getMessage(): string
    {
        return sprintf(
            'Votre demande de congé du %s au %s a été approuvée.',
            $this->formatDate($this->conge->date_debut),
            $this->formatDate($this->conge->date_fin)
        );
    }
}