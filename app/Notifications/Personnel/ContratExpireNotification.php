<?php
namespace App\Notifications\Personnel;

use App\Domain\Personnel\Models\Contrat;
use App\Notifications\BaseNotification;

class ContratExpireNotification extends BaseNotification
{
    protected string $couleur  = 'warning';
    protected string $icone    = 'fa-file-signature';
    protected string $priorite = 'high';

    public function __construct(public Contrat $contrat)
    {
        $this->url = route('rh.employes.show', $contrat->employee_id);
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    protected function getTitre(): string
    {
        return '⚠️ Contrat arrivant à échéance';
    }

    protected function getMessage(): string
    {
        return sprintf(
            'Le contrat %s de %s expire le %s.',
            $this->contrat->numero,
            $this->contrat->employe?->nom_complet ?? '—',
            $this->formatDate($this->contrat->date_fin)
        );
    }

    public function toMail(object $notifiable)
    {
        return (new \App\Mail\NotificationMail(
            $this->getTitre(),
            $this->getMessage(),
            $this->url,
            $this->couleur,
            'Gérer le contrat',
            [
                'Employé'    => $this->contrat->employe?->nom_complet,
                'Matricule'  => $this->contrat->employe?->matricule,
                'Type'       => $this->contrat->type_label,
                'Fin contrat'=> $this->formatDate($this->contrat->date_fin),
                'Jours rest.'=> $this->contrat->jours_restants . ' jours',
            ]
        ))->to($notifiable->routeNotificationFor('mail') ?? $notifiable->email);
    }
}