<?php
namespace App\Notifications\Approvisionnement;

use App\Domain\Approvisionnement\Models\DemandeAchat;
use App\Notifications\BaseNotification;

class DemandeAchatValideeNotification extends BaseNotification
{
    protected string $couleur  = 'success';
    protected string $icone    = 'fa-file-circle-check';
    protected string $priorite = 'normal';

    public function __construct(public DemandeAchat $demande)
    {
        $this->url = route('logistique.demandes-achat.show', $demande);
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    protected function getTitre(): string
    {
        return '✔️ Demande d\'achat validée : ' . $this->demande->numero;
    }

    protected function getMessage(): string
    {
        return sprintf(
            'La demande d\'achat %s du chantier « %s » a été validée. '
            . 'Prête pour transformer en bon de commande.',
            $this->demande->numero,
            $this->demande->projet?->code ?? '—'
        );
    }
}