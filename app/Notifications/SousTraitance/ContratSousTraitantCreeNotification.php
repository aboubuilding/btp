<?php
namespace App\Notifications\SousTraitance;

use App\Domain\SousTraitance\Models\ContratSousTraitant;
use App\Notifications\BaseNotification;

class ContratSousTraitantCreeNotification extends BaseNotification
{
    protected string $couleur  = 'success';
    protected string $icone    = 'fa-handshake';
    protected string $priorite = 'normal';

    public function __construct(public ContratSousTraitant $contrat)
    {
        $this->url = route('soustraitance.contrats.show', $contrat);
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    protected function getTitre(): string
    {
        return '🤝 Nouveau contrat sous-traitant';
    }

    protected function getMessage(): string
    {
        return sprintf(
            'Contrat %s créé avec « %s » pour le chantier « %s » — Montant : %s.',
            $this->contrat->numero_contrat,
            $this->contrat->soustraitant?->entreprise ?? '—',
            $this->contrat->projet?->code ?? '—',
            $this->formatMontant((float) $this->contrat->montant)
        );
    }
}