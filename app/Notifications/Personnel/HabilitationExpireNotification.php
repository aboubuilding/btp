<?php
namespace App\Notifications\Personnel;

use App\Domain\Personnel\Models\DocumentEmploye;
use App\Notifications\BaseNotification;

class HabilitationExpireNotification extends BaseNotification
{
    protected string $couleur  = 'warning';
    protected string $icone    = 'fa-certificate';
    protected string $priorite = 'high';

    public function __construct(public DocumentEmploye $document)
    {
        $this->url = route('rh.employes.show', $document->employee_id);
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    protected function getTitre(): string
    {
        return '📜 Habilitation bientôt expirée';
    }

    protected function getMessage(): string
    {
        $jours = max(0, (int) now()->diffInDays($this->document->date_expiration, false));

        return sprintf(
            'L\'habilitation « %s » de %s expire dans %d jour(s) — le %s.',
            $this->document->nom,
            $this->document->employe?->nom_complet ?? '—',
            $jours,
            $this->formatDate($this->document->date_expiration)
        );
    }
}