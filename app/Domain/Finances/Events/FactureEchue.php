<?php
namespace App\Domain\Finances\Events;

use App\Domain\Finances\Models\Facture;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FactureEchue
{
    use Dispatchable, SerializesModels;

    public function __construct(public Facture $facture) {}

    public function toArray(): array
    {
        return [
            'facture_id'      => $this->facture->id,
            'numero'          => $this->facture->numero_facture,
            'type'            => $this->facture->type,
            'montant_ttc'     => (float) $this->facture->montant_ttc,
            'reste_a_payer'   => (float) $this->facture->reste_a_payer,
            'jours_retard'    => $this->facture->jours_retard,
            'date_echeance'   => $this->facture->date_echeance?->toDateString(),
        ];
    }
}