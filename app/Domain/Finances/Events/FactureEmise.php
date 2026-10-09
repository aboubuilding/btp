<?php
namespace App\Domain\Finances\Events;

use App\Domain\Finances\Models\Facture;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FactureEmise
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
            'net_a_payer'     => (float) $this->facture->net_a_payer,
            'date_facture'    => $this->facture->date_facture?->toDateString(),
            'date_echeance'   => $this->facture->date_echeance?->toDateString(),
            'facturable_id'   => $this->facture->id_facturable,
        ];
    }
}