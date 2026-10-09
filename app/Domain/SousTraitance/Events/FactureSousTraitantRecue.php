<?php
namespace App\Domain\SousTraitance\Events;

use App\Domain\SousTraitance\Models\FactureSousTraitant;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FactureSousTraitantRecue
{
    use Dispatchable, SerializesModels;

    public function __construct(public FactureSousTraitant $facture) {}

    public function toArray(): array
    {
        return [
            'facture_id'    => $this->facture->id,
            'numero'        => $this->facture->numero,
            'contrat_id'    => $this->facture->contrat_sous_traitant_id,
            'montant_ttc'   => (float) $this->facture->montant_ttc,
            'date_facture'  => $this->facture->date_facture?->toDateString(),
        ];
    }
}