<?php
namespace App\Domain\ParcMateriel\Events;

use App\Domain\ParcMateriel\Models\PanneEquipement;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PanneDeclaree
{
    use Dispatchable, SerializesModels;

    public function __construct(public PanneEquipement $panne) {}

    public function toArray(): array
    {
        return [
            'panne_id'      => $this->panne->id,
            'equipement_id' => $this->panne->equipement_id,
            'equipement_nom'=> $this->panne->equipement?->nom,
            'date_panne'    => $this->panne->date_panne?->toDateString(),
            'description'   => $this->panne->description,
            'declare_par'   => $this->panne->declare_par,
        ];
    }
}