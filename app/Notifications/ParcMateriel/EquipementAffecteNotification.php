<?php
namespace App\Notifications\ParcMateriel;

use App\Domain\ParcMateriel\Models\AffectationEquipement;
use App\Notifications\BaseNotification;

class EquipementAffecteNotification extends BaseNotification
{
    protected string $couleur  = 'info';
    protected string $icone    = 'fa-map-location-dot';
    protected string $priorite = 'normal';

    public function __construct(public AffectationEquipement $affectation)
    {
        $this->url = route('materiel.equipements.show', $affectation->equipement_id);
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    protected function getTitre(): string
    {
        return '🚜 Engin affecté';
    }

    protected function getMessage(): string
    {
        return sprintf(
            'L\'engin « %s » a été affecté au chantier « %s » depuis le %s.',
            $this->affectation->equipement?->nom ?? '—',
            $this->affectation->projet?->code ?? '—',
            $this->formatDate($this->affectation->date_debut)
        );
    }
}