<?php
namespace App\Domain\Personnel\Events;

use App\Domain\Personnel\Models\Contrat;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContratExpireBientot
{
    use Dispatchable, SerializesModels;

    public function __construct(public Contrat $contrat) {}

    public function toArray(): array
    {
        return [
            'contrat_id'   => $this->contrat->id,
            'numero'       => $this->contrat->numero,
            'employe_id'   => $this->contrat->employee_id,
            'employe_nom'  => $this->contrat->employe?->nom_complet,
            'date_fin'     => $this->contrat->date_fin?->toDateString(),
            'jours_restants' => $this->contrat->jours_restants,
        ];
    }
}