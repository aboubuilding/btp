<?php
namespace App\Domain\SousTraitance\Events;

use App\Domain\SousTraitance\Models\PaiementSousTraitant;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaiementSousTraitantEnregistre
{
    use Dispatchable, SerializesModels;

    public function __construct(public PaiementSousTraitant $paiement) {}

    public function toArray(): array
    {
        return [
            'paiement_id'  => $this->paiement->id,
            'facture_id'   => $this->paiement->facture_sous_traitant_id,
            'montant'      => (float) $this->paiement->montant,
            'date'         => $this->paiement->date_paiement?->toDateString(),
            'mode'         => $this->paiement->mode,
        ];
    }
}