<?php
namespace App\Notifications\Commercial;

use App\Domain\Commercial\Models\CautionMarche;
use App\Notifications\BaseNotification;

class CautionExpiranteNotification extends BaseNotification
{
    protected string $couleur  = 'warning';
    protected string $icone    = 'fa-shield-halved';
    protected string $priorite = 'high';

    public function __construct(public CautionMarche $caution)
    {
        $this->url = route('commercial.marches.show', $caution->marche_id);
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    protected function getTitre(): string
    {
        return '⚠️ Caution bancaire arrivant à échéance';
    }

    protected function getMessage(): string
    {
        $jours = max(0, (int) now()->diffInDays($this->caution->date_echeance, false));

        return sprintf(
            'La caution %s du marché « %s » expire dans %d jour(s) — %s.',
            $this->caution->type_label,
            $this->caution->marche?->reference ?? '—',
            $jours,
            $this->formatDate($this->caution->date_echeance)
        );
    }
}