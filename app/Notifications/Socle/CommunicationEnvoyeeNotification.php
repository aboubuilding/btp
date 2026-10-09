<?php
namespace App\Notifications\Socle;

use App\Domain\Socle\Models\Communication;
use App\Notifications\BaseNotification;

class CommunicationEnvoyeeNotification extends BaseNotification
{
    protected string $couleur  = 'success';
    protected string $icone    = 'fa-paper-plane';
    protected string $priorite = 'normal';

    public function __construct(public Communication $communication)
    {
        $this->url = route('communications.show', $communication);
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    protected function getTitre(): string
    {
        return '✅ Communication envoyée';
    }

    protected function getMessage(): string
    {
        return sprintf(
            'Votre message « %s » a été envoyé à %d destinataire(s).',
            $this->communication->sujet,
            $this->communication->nb_destinataires
        );
    }
}