<?php
namespace App\Notifications\ParcMateriel;

use App\Domain\ParcMateriel\Models\MaintenanceEquipement;
use App\Notifications\BaseNotification;

class MaintenanceDueNotification extends BaseNotification
{
    protected string $couleur  = 'warning';
    protected string $icone    = 'fa-screwdriver-wrench';
    protected string $priorite = 'high';

    public function __construct(public MaintenanceEquipement $maintenance)
    {
        $this->url = route('materiel.equipements.show', $maintenance->equipement_id);
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    protected function getTitre(): string
    {
        return '🔧 Maintenance à planifier : ' . $this->maintenance->equipement?->nom;
    }

    protected function getMessage(): string
    {
        $jours = max(0, (int) now()->diffInDays($this->maintenance->prochaine_echeance, false));
        return sprintf(
            'La maintenance %s de l\'engin %s est due dans %d jour(s) — %s.',
            $this->maintenance->type,
            $this->maintenance->equipement?->nom ?? '—',
            $jours,
            $this->formatDate($this->maintenance->prochaine_echeance)
        );
    }
}