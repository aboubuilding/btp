<?php
namespace App\Notifications\SousTraitance;

use App\Domain\SousTraitance\Models\ContratSousTraitant;
use App\Notifications\BaseNotification;

class PaiementPlafondAtteintNotification extends BaseNotification
{
    protected string $couleur  = 'warning';
    protected string $icone    = 'fa-hand-holding-dollar';
    protected string $priorite = 'high';

    public function __construct(public ContratSousTraitant $contrat)
    {
        $this->url = route('soustraitance.contrats.show', $contrat);
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    protected function getTitre(): string
    {
        return '⚠️ Plafond de paiement atteint';
    }

    protected function getMessage(): string
    {
        return sprintf(
            'Le sous-traitant « %s » du chantier « %s » a atteint %d%% de son plafond de paiement.',
            $this->contrat->soustraitant?->entreprise ?? '—',
            $this->contrat->projet?->code ?? '—',
            round($this->contrat->taux_avancement)
        );
    }
}