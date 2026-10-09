<?php
namespace App\Notifications\Finances;

use App\Domain\Finances\Models\Depense;
use App\Notifications\BaseNotification;

class DepenseEnAttenteNotification extends BaseNotification
{
    protected string $couleur  = 'warning';
    protected string $icone    = 'fa-hourglass-half';
    protected string $priorite = 'normal';

    public function __construct(public Depense $depense)
    {
        $this->url = route('finances.depenses.index');
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    protected function getTitre(): string
    {
        return '⏳ Dépense en attente d\'approbation';
    }

    protected function getMessage(): string
    {
        return sprintf(
            'Dépense de %s sur le chantier « %s » (catégorie : %s) en attente d\'approbation.',
            $this->formatMontant((float) $this->depense->montant),
            $this->depense->projet?->code ?? '—',
            $this->depense->categorie_label
        );
    }
}