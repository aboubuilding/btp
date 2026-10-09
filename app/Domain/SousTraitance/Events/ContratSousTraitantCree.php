<?php
namespace App\Domain\SousTraitance\Events;

use App\Domain\SousTraitance\Models\ContratSousTraitant;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContratSousTraitantCree
{
    use Dispatchable, SerializesModels;

    public function __construct(public ContratSousTraitant $contrat) {}

    public function toArray(): array
    {
        return [
            'contrat_id'    => $this->contrat->id,
            'numero'        => $this->contrat->numero_contrat,
            'soustraitant'  => $this->contrat->soustraitant?->entreprise,
            'projet_id'     => $this->contrat->projet_id,
            'montant'       => (float) $this->contrat->montant,
            'date_debut'    => $this->contrat->date_debut?->toDateString(),
        ];
    }
}