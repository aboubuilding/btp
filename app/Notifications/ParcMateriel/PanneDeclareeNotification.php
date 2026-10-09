<?php
namespace App\Notifications\ParcMateriel;

use App\Domain\ParcMateriel\Models\PanneEquipement;
use App\Notifications\BaseNotification;

class PanneDeclareeNotification extends BaseNotification
{
    protected string $couleur  = 'danger';
    protected string $icone    = 'fa-tools';
    protected string $priorite = 'high';

    public function __construct(public PanneEquipement $panne)
    {
        $this->url = route('materiel.equipements.show', $panne->equipement_id);
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    protected function getTitre(): string
    {
        return '🔧 Panne déclarée : ' . $this->panne->equipement?->nom;
    }

    protected function getMessage(): string
    {
        return sprintf(
            "L'engin « %s » (code %s) est en panne depuis le %s.\nDescription : %s",
            $this->panne->equipement?->nom ?? '—',
            $this->panne->equipement?->code ?? '—',
            $this->formatDate($this->panne->date_panne),
            $this->tronquer($this->panne->description)
        );
    }

    public function toMail(object $notifiable)
    {
        return (new \App\Mail\NotificationMail(
            $this->getTitre(),
            $this->getMessage(),
            $this->url,
            $this->couleur,
            'Intervenir',
            [
                'Engin'         => $this->panne->equipement?->nom . ' (' . $this->panne->equipement?->code . ')',
                'Date'          => $this->formatDate($this->panne->date_panne),
                'Heures immob.' => $this->panne->heures_immobilisation . ' h',
                'Coût estimé'   => $this->formatMontant((float) $this->panne->cout_reparation),
            ]
        ))->to($notifiable->routeNotificationFor('mail') ?? $notifiable->email);
    }
}