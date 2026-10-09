<?php
namespace App\Domain\SousTraitance\Events;

use App\Domain\SousTraitance\Models\ContratSousTraitant;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaiementPlafondAtteint
{
    use Dispatchable, SerializesModels;

    public function __construct(public ContratSousTraitant $contrat) {}

    public function toArray(): array
    {
        return [
            'contrat_id'    => $this->contrat->id,
            'numero'        => $this->contrat->numero_contrat,
            'soustraitant'  => $this->contrat->soustraitant?->entreprise,
            'total_paye'    => $this->contrat->total_paye,
            'plafond'       => $this->contrat->plafond_paiement,
            'taux'          => $this->contrat->taux_avancement,
        ];
    }
}