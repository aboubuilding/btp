<?php
namespace App\Domain\ParcMateriel\Events;

use App\Domain\ParcMateriel\Models\{AffectationEquipement, Equipement};
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EquipementAffecte
{
    use Dispatchable, SerializesModels;

    public function __construct(public AffectationEquipement $affectation) {}

    public function toArray(): array
    {
        return [
            'affectation_id' => $this->affectation->id,
            'equipement_id'  => $this->affectation->equipement_id,
            'projet_id'      => $this->affectation->projet_id,
            'date_debut'     => $this->affectation->date_debut?->toDateString(),
            'compteur_debut' => (float) $this->affectation->compteur_debut,
        ];
    }
}