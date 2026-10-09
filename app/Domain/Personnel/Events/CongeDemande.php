<?php
namespace App\Domain\Personnel\Events;

use App\Domain\Personnel\Models\DemandeConge;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CongeDemande
{
    use Dispatchable, SerializesModels;

    public function __construct(public DemandeConge $conge) {}

    public function toArray(): array
    {
        return [
            'conge_id'      => $this->conge->id,
            'employe_id'    => $this->conge->employee_id,
            'employe_nom'   => $this->conge->employe?->nom_complet,
            'type'          => $this->conge->typeConge?->nom,
            'date_debut'    => $this->conge->date_debut?->toDateString(),
            'date_fin'      => $this->conge->date_fin?->toDateString(),
            'nombre_jours'  => $this->conge->nombre_jours,
        ];
    }
}