<?php
namespace App\Notifications\Finances;

use App\Domain\Finances\Models\Depense;
use App\Notifications\BaseNotification;

class DepenseApprouveeNotification extends BaseNotification
{
    protected string $couleur  = 'success';
    protected string $icone    = 'fa-check-circle';
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
        return '✅ Dépense approuvée';
    }

    protected function getMessage(): string
    {
        return sprintf(
            'Dépense de %s sur le chantier « %s » approuvée.',
            $this->formatMontant((float) $this->depense->montant),
            $this->depense->projet?->code ?? '—'
        );
    }
}